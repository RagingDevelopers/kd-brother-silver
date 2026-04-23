<?php

if (!function_exists('get_item_data')) {
    function get_item_data($metal_type_id)
    {
        $CI = &get_instance();
        $metal_type_id = (int) $metal_type_id;
        $post = xss_clean($CI->input->post());

        $CI->db->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

        $openingQuery = "SELECT *
            FROM (
                SELECT touch, weight, 'garnu given' AS type, garnu_item.created_at AS created_at
                FROM garnu_item
                WHERE garnu_item.metal_type_id = ? AND garnu_item.is_bhuko_used = 0
                UNION ALL

                SELECT touch, weight, 'garnu receive' AS type, receive_garnu.created_at AS created_at
                FROM receive_garnu
                WHERE receive_garnu.metal_type_id = ? AND receive_garnu.is_bhuko_used = 0
                UNION ALL

                SELECT touch, weight, 'testing given' AS type, given_testing_item.created_at AS created_at
                FROM given_testing_item
                WHERE given_testing_item.metal_type_id = ? AND given_testing_item.is_bhuko_used = 0
                UNION ALL

                SELECT touch, weight, 'given testing receive' AS type, receive_given_testing.created_at AS created_at
                FROM receive_given_testing
                WHERE receive_given_testing.metal_type_id = ? AND receive_given_testing.is_bhuko_used = 0
                UNION ALL

                SELECT touch, weight, 'dhal receive' AS type, receive_garnu_dhal.created_at AS created_at
                FROM receive_garnu_dhal
                WHERE receive_garnu_dhal.metal_type_id = ? AND receive_garnu_dhal.is_bhuko_used = 0
                UNION ALL

                SELECT touch, weight, 'process given' AS type, process_metal_type.created_at AS created_at
                FROM process_metal_type
                LEFT JOIN given ON process_metal_type.given_id = given.id
                WHERE process_metal_type.metal_type_id = ? AND process_metal_type.is_bhuko_used = 0
                UNION ALL

                SELECT purity AS touch, gross AS weight, 'jama' AS type, created_at
                FROM jama
                WHERE type = 'fine' AND jama.metal_type_id = ? AND jama.is_bhuko_used = 0
                UNION ALL

                SELECT purity AS touch, gross AS weight, 'baki' AS type, created_at
                FROM baki
                WHERE type = 'fine' AND baki.metal_type_id = ? AND baki.is_bhuko_used = 0
                UNION ALL

                SELECT touch, weight, 'main garnu given' AS type, main_garnu_item.created_at AS created_at
                FROM main_garnu_item
                WHERE main_garnu_item.metal_type_id = ? AND main_garnu_item.is_bhuko_used = 0
            ) AS opening_records
            ORDER BY created_at ASC";

        $bindings = array_fill(0, 9, $metal_type_id);
        $openingResult = $CI->db->query($openingQuery, $bindings)->result_array();

        $metal_closing_stock_map = [];
        $positive_types = ['given testing receive', 'garnu receive', 'dhal receive', 'process given', 'jama'];
        $negative_types = ['testing given', 'garnu given', 'baki', 'main garnu given'];

        foreach ($openingResult as $record) {
            $touch = abs((float) $record['touch']);
            $weight = abs((float) $record['weight']);
            $type = $record['type'];

            if (!isset($metal_closing_stock_map[$touch])) {
                $metal_closing_stock_map[$touch] = [
                    'touch' => $touch,
                    'weight' => 0,
                ];
            }

            if (in_array($type, $positive_types, true)) {
                $metal_closing_stock_map[$touch]['weight'] += $weight;
            } elseif (in_array($type, $negative_types, true)) {
                $metal_closing_stock_map[$touch]['weight'] -= $weight;
            }
        }

        $metal_closing_stock = array_values($metal_closing_stock_map);

        if ($metal_type_id === 8) {
            $bhuko = $CI->db->query("SELECT touch, weight FROM common_bhuko LIMIT 1")->row_array();
            if (!empty($bhuko)) {
                $metal_closing_stock[] = [
                    'touch' => isset($bhuko['touch']) ? (float) $bhuko['touch'] : 0,
                    'weight' => isset($bhuko['weight']) ? (float) $bhuko['weight'] : 0,
                ];
            }
        }

        $parm = [
            'row_material_id' => isset($post['row_material_id']) ? (int) $post['row_material_id'] : (isset($post['metal_type_id']) ? (int) $post['metal_type_id'] : 0),
            'given_id' => isset($post['given_id']) ? (int) $post['given_id'] : 0,
            'garnu_id' => isset($post['garnu_id']) ? (int) $post['garnu_id'] : 0,
            'lot_wise_rm_id' => isset($post['lot_wise_rm_id']) ? (int) $post['lot_wise_rm_id'] : 0,
            'metal_closing_stock' => $metal_closing_stock,
        ];

        $formatted_data = array_map(function ($entry) {
            if (isset($entry['fine']) && isset($entry['average_touch'])) {
                return $entry['touch'] . ' - ' . abs($entry['weight']) . ' KG (Fine: ' . $entry['fine'] . ', Average Touch: ' . $entry['average_touch'] . ')';
            }

            return $entry['touch'] . ' - ' . abs($entry['weight']) . ' KG';
        }, $metal_closing_stock);

        return [
            'opening_records' => $openingResult,
            'metal_closing_stock' => $metal_closing_stock,
            'lot_data' => lot_data($parm),
            'formatted_data' => $formatted_data,
        ];
    }
}

if (!function_exists('lot_data')) {
    function lot_data($parm = [])
    {
        $CI = &get_instance();

        $row_material_id = isset($parm['row_material_id']) ? (int) $parm['row_material_id'] : 0;
        $given_id = isset($parm['given_id']) ? (int) $parm['given_id'] : 0;
        $garnu_id = isset($parm['garnu_id']) ? (int) $parm['garnu_id'] : 0;
        $lot_wise_rm_id = isset($parm['lot_wise_rm_id']) ? (int) $parm['lot_wise_rm_id'] : 0;
        $metal_closing_stock = isset($parm['metal_closing_stock']) && is_array($parm['metal_closing_stock']) ? $parm['metal_closing_stock'] : [];

        if ($row_material_id > 0) {
            if ($given_id > 0 && $garnu_id > 0) {
                $query = "
                    SELECT * FROM (
                        SELECT l.id, l.touch, l.code, l.rem_weight, l.rem_quantity
                        FROM lot_wise_rm l
                        JOIN given_row_material g ON l.id = g.lot_wise_rm_id
                        WHERE l.row_material_id = ? AND g.lot_wise_rm_id != 0 AND g.given_id = ? AND g.garnu_id = ?
                        UNION
                        SELECT l.id, l.touch, l.code, l.rem_weight, l.rem_quantity
                        FROM lot_wise_rm l
                        WHERE l.row_material_id = ? AND l.is_complated = 'NO'
                    ) AS result_set
                    ORDER BY id DESC";

                $lot_data = $CI->db->query($query, [$row_material_id, $given_id, $garnu_id, $row_material_id])->result_array();
            } else {
                $lot_data = $CI->db->select('id,touch,code,rem_weight,rem_quantity')
                    ->from('lot_wise_rm')
                    ->where(['row_material_id' => $row_material_id, 'is_complated' => 'NO'])
                    ->order_by('id', 'DESC')
                    ->get()
                    ->result_array();
            }

            if ($lot_wise_rm_id > 0) {
                $data2 = $CI->db->select('id,touch,code,rem_weight,rem_quantity')
                    ->from('lot_wise_rm')
                    ->where(['id' => $lot_wise_rm_id, 'row_material_id' => $row_material_id])
                    ->order_by('id', 'DESC')
                    ->get()
                    ->result_array();

                if (!empty($data2)) {
                    $merged_lot_data = array_merge($lot_data, $data2);
                    $unique_lot_data = [];
                    foreach ($merged_lot_data as $lot_row) {
                        $unique_lot_data[$lot_row['id']] = $lot_row;
                    }
                    $lot_data = array_values($unique_lot_data);
                }
            }

            return $lot_data;
        }

        return array_values(array_filter(array_map(function ($entry) {
            $touch = isset($entry['touch']) ? (float) $entry['touch'] : 0;
            $weight = isset($entry['weight']) ? abs((float) $entry['weight']) : 0;

            if ($weight <= 0) {
                return null;
            }

            return [
                'touch' => $touch,
                'weight' => $weight,
                'display_text' => $touch . ' - ' . $weight . ' KG',
            ];
        }, $metal_closing_stock)));
    }
}
