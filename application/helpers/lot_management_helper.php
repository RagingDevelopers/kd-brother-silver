<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('lot_management')) {
	function lot_management(array $data = [])
	{
		$CI = &get_instance();

		$db = $CI->db;

		if (!isset($data['id']) && isset($data['lot_id'])) {
			$data['id'] = $data['lot_id'];
		}

		if (empty($data['row_material_id']) && !empty($data['raw_material_id'])) {
			$data['row_material_id'] = $data['raw_material_id'];
		}

		$id = isset($data['id']) ? (int) $data['id'] : 0;
		$action = ($id > 0) ? 'update' : 'create';

		if ($action === 'create' && empty($data['row_material_id'])) {
			return false;
		}

		switch ($action) {
			case 'update':
				$id = (int) $data['id'];
				$rowMaterialId = isset($data['row_material_id']) ? (int) $data['row_material_id'] : 0;

				$weight = isset($data['weight']) ? (float) $data['weight'] : null;
				$quantity = isset($data['quantity']) ? (float) $data['quantity'] : null;
				$touch = isset($data['touch']) ? (float) $data['touch'] : null;

				$givenWeightDiff = isset($data['given_weight_diff']) ? (float) $data['given_weight_diff'] : 0;
				$givenQuantityDiff = isset($data['given_quantity_diff']) ? (float) $data['given_quantity_diff'] : 0;

				$remWeightDiff = array_key_exists('rem_weight_diff', $data)
					? (float) $data['rem_weight_diff']
					: (-1 * $givenWeightDiff);

				$remQuantityDiff = array_key_exists('rem_quantity_diff', $data)
					? (float) $data['rem_quantity_diff']
					: (-1 * $givenQuantityDiff);

				$movement = isset($data['movement']) ? strtolower((string) $data['movement']) : '';

				$oldWeight = isset($data['old_weight']) ? (float) $data['old_weight'] : 0;
				$oldQuantity = isset($data['old_quantity']) ? (float) $data['old_quantity'] : 0;

				$oldLotId = isset($data['old_lot_wise_rm_id'])
					? (int) $data['old_lot_wise_rm_id']
					: $id;

				$oldRowMaterialId = isset($data['old_row_material_id'])
					? (int) $data['old_row_material_id']
					: $rowMaterialId;

				$receiveWeightDiff = array_key_exists('receive_weight_diff', $data)
					? (float) $data['receive_weight_diff']
					: null;

				$receiveQuantityDiff = array_key_exists('receive_quantity_diff', $data)
					? (float) $data['receive_quantity_diff']
					: null;

				$isNewDetail = !empty($data['is_new_detail'])
					|| (isset($data['rowid']) && (int) $data['rowid'] === 0);

				$updateLotValues = array_key_exists('update_lot_values', $data)
					? (bool) $data['update_lot_values']
					: !in_array($movement, ['given', 'receive'], true);

				/*
				 * add_lot_values_by_diff is for RECEIVE stock entry.
				 *
				 * It updates these base columns by difference:
				 * weight, quantity
				 *
				 * And also updates:
				 * receive_weight, receive_quantity, rem_weight, rem_quantity
				 */
				$addLotValuesByDiff = !empty($data['add_lot_values_by_diff']);
				$onlyFourColumns = !empty($data['only_four_columns']);
				$baseWeightDiff = 0;
				$baseQuantityDiff = 0;

				$strictRowMaterialMatch = !empty($data['strict_row_material_match']);
				$hasUpdate = false;

				$lotExists = function ($lotId, $materialId) use ($db) {
					$lotId = (int) $lotId;
					$materialId = (int) $materialId;

					if ($lotId <= 0 || $materialId <= 0) {
						return false;
					}

					return $db
						->select('id')
						->where('id', $lotId)
						->where('row_material_id', $materialId)
						->limit(1)
						->get('lot_wise_rm')
						->num_rows() > 0;
				};

				if ($strictRowMaterialMatch && $rowMaterialId > 0) {
					if (!$lotExists($id, $rowMaterialId)) {
						return false;
					}

					if ($oldLotId > 0 && $oldLotId !== $id && $oldRowMaterialId > 0) {
						if (!$lotExists($oldLotId, $oldRowMaterialId)) {
							return false;
						}
					}
				}

				if ($movement === 'given') {
					$newWeight = $weight !== null ? $weight : 0;
					$newQuantity = $quantity !== null ? $quantity : 0;

					if ($oldLotId > 0 && $oldLotId !== $id) {
						if ($oldWeight != 0 || $oldQuantity != 0) {
							$db->where('id', $oldLotId);

							if ($strictRowMaterialMatch && $oldRowMaterialId > 0) {
								$db->where('row_material_id', $oldRowMaterialId);
							}

							if ($oldWeight != 0) {
								$db->set('given_weight', 'COALESCE(given_weight, 0) - (' . $oldWeight . ')', false);
								$db->set('rem_weight', 'COALESCE(rem_weight, 0) + (' . $oldWeight . ')', false);
							}

							if ($oldQuantity != 0) {
								$db->set('given_quantity', 'COALESCE(given_quantity, 0) - (' . $oldQuantity . ')', false);
								$db->set('rem_quantity', 'COALESCE(rem_quantity, 0) + (' . $oldQuantity . ')', false);
							}

							if (!$db->update('lot_wise_rm')) {
								return false;
							}
						}

						$givenWeightDiff = $newWeight;
						$givenQuantityDiff = $newQuantity;
					} else {
						$givenWeightDiff = $isNewDetail ? $newWeight : ($newWeight - $oldWeight);
						$givenQuantityDiff = $isNewDetail ? $newQuantity : ($newQuantity - $oldQuantity);
					}

					$remWeightDiff = -1 * $givenWeightDiff;
					$remQuantityDiff = -1 * $givenQuantityDiff;
				} elseif ($movement === 'receive') {
					$newWeight = $weight !== null ? $weight : 0;
					$newQuantity = $quantity !== null ? $quantity : 0;

					if ($oldLotId > 0 && $oldLotId !== $id) {
						if ($oldWeight != 0 || $oldQuantity != 0) {
							$db->where('id', $oldLotId);

							if ($strictRowMaterialMatch && $oldRowMaterialId > 0) {
								$db->where('row_material_id', $oldRowMaterialId);
							}

							if (($updateLotValues || $addLotValuesByDiff) && $oldWeight != 0) {
								$db->set('weight', 'COALESCE(weight, 0) - (' . $oldWeight . ')', false);
							}

							if (($updateLotValues || $addLotValuesByDiff) && $oldQuantity != 0) {
								$db->set('quantity', 'COALESCE(quantity, 0) - (' . $oldQuantity . ')', false);
							}

							if ($oldWeight != 0) {
								if (!$onlyFourColumns) {
									$db->set('receive_weight', 'COALESCE(receive_weight, 0) - (' . $oldWeight . ')', false);
								}

								$db->set('rem_weight', 'COALESCE(rem_weight, 0) - (' . $oldWeight . ')', false);
							}

							if ($oldQuantity != 0) {
								if (!$onlyFourColumns) {
									$db->set('receive_quantity', 'COALESCE(receive_quantity, 0) - (' . $oldQuantity . ')', false);
								}

								$db->set('rem_quantity', 'COALESCE(rem_quantity, 0) - (' . $oldQuantity . ')', false);
							}

							if (!$db->update('lot_wise_rm')) {
								return false;
							}
						}

						$receiveWeightDiff = $receiveWeightDiff !== null ? $receiveWeightDiff : $newWeight;
						$receiveQuantityDiff = $receiveQuantityDiff !== null ? $receiveQuantityDiff : $newQuantity;
					} else {
						$receiveWeightDiff = $receiveWeightDiff !== null
							? $receiveWeightDiff
							: ($isNewDetail ? $newWeight : ($newWeight - $oldWeight));

						$receiveQuantityDiff = $receiveQuantityDiff !== null
							? $receiveQuantityDiff
							: ($isNewDetail ? $newQuantity : ($newQuantity - $oldQuantity));
					}

					$remWeightDiff = $receiveWeightDiff;
					$remQuantityDiff = $receiveQuantityDiff;

					if ($addLotValuesByDiff) {
						$baseWeightDiff = $receiveWeightDiff;
						$baseQuantityDiff = $receiveQuantityDiff;
					}
				}

				$db->where('id', $id);

				if ($strictRowMaterialMatch && $rowMaterialId > 0) {
					$db->where('row_material_id', $rowMaterialId);
				}

				if ($updateLotValues && !empty($data['row_material_id'])) {
					$db->set('row_material_id', $rowMaterialId);
					$hasUpdate = true;
				}

				if ($addLotValuesByDiff) {
					if ($baseWeightDiff != 0) {
						$db->set('weight', 'COALESCE(weight, 0) + (' . $baseWeightDiff . ')', false);
						$hasUpdate = true;
					}

					if ($baseQuantityDiff != 0) {
						$db->set('quantity', 'COALESCE(quantity, 0) + (' . $baseQuantityDiff . ')', false);
						$hasUpdate = true;
					}
				} else {
					if ($updateLotValues && $weight !== null) {
						$db->set('weight', $weight);
						$hasUpdate = true;
					}

					if ($updateLotValues && $quantity !== null) {
						$db->set('quantity', $quantity);
						$hasUpdate = true;
					}
				}

				if ($updateLotValues && $touch !== null) {
					$db->set('touch', $touch);
					$hasUpdate = true;
				}

				if ($givenWeightDiff != 0) {
					$db->set('given_weight', 'COALESCE(given_weight, 0) + (' . $givenWeightDiff . ')', false);
					$hasUpdate = true;
				}

				if ($givenQuantityDiff != 0) {
					$db->set('given_quantity', 'COALESCE(given_quantity, 0) + (' . $givenQuantityDiff . ')', false);
					$hasUpdate = true;
				}

				if (!$onlyFourColumns && isset($receiveWeightDiff) && $receiveWeightDiff != 0) {
					$db->set('receive_weight', 'COALESCE(receive_weight, 0) + (' . $receiveWeightDiff . ')', false);
					$hasUpdate = true;
				}

				if (!$onlyFourColumns && isset($receiveQuantityDiff) && $receiveQuantityDiff != 0) {
					$db->set('receive_quantity', 'COALESCE(receive_quantity, 0) + (' . $receiveQuantityDiff . ')', false);
					$hasUpdate = true;
				}

				if ($remWeightDiff != 0) {
					$db->set('rem_weight', 'COALESCE(rem_weight, 0) + (' . $remWeightDiff . ')', false);
					$hasUpdate = true;
				}

				if ($remQuantityDiff != 0) {
					$db->set('rem_quantity', 'COALESCE(rem_quantity, 0) + (' . $remQuantityDiff . ')', false);
					$hasUpdate = true;
				}

				if (!$hasUpdate) {
					// Prevent leftover WHERE clauses from leaking into next query.
					$db->reset_query();
					return true;
				}

				return $db->update('lot_wise_rm');

			default:
				$type = isset($data['type']) ? strtoupper((string) $data['type']) : 'PURCHASE';

				$weight = isset($data['weight']) ? (float) $data['weight'] : 0;
				$quantity = isset($data['quantity']) ? (float) $data['quantity'] : 0;

				$remWeight = array_key_exists('rem_weight', $data)
					? (float) $data['rem_weight']
					: $weight;

				$remQuantity = array_key_exists('rem_quantity', $data)
					? (float) $data['rem_quantity']
					: $quantity;

				$givenWeight = isset($data['given_weight']) ? (float) $data['given_weight'] : 0;
				$givenQuantity = isset($data['given_quantity']) ? (float) $data['given_quantity'] : 0;

				$receiveWeight = isset($data['receive_weight']) ? (float) $data['receive_weight'] : 0;
				$receiveQuantity = isset($data['receive_quantity']) ? (float) $data['receive_quantity'] : 0;

				if ($type === 'RECEIVE') {
					$receiveWeight = array_key_exists('receive_weight', $data) ? $receiveWeight : $weight;
					$receiveQuantity = array_key_exists('receive_quantity', $data) ? $receiveQuantity : $quantity;
				} elseif ($type === 'GIVEN') {
					$givenWeight = array_key_exists('given_weight', $data) ? $givenWeight : $weight;
					$givenQuantity = array_key_exists('given_quantity', $data) ? $givenQuantity : $quantity;
				}

				$insert = [
					'user_id' => isset($data['user_id']) ? (int) $data['user_id'] : 0,
					'row_material_id' => (int) $data['row_material_id'],
					'weight' => $weight,
					'rem_weight' => $remWeight,
					'touch' => isset($data['touch']) ? (float) $data['touch'] : 0,
					'quantity' => $quantity,
					'rem_quantity' => $remQuantity,
					'given_weight' => $givenWeight,
					'given_quantity' => $givenQuantity,
					'receive_weight' => $receiveWeight,
					'receive_quantity' => $receiveQuantity,
					'purchase_detail_id' => isset($data['purchase_detail_id']) ? (int) $data['purchase_detail_id'] : 0,
					'code' => isset($data['code']) ? (string) $data['code'] : '',
					'creation_date' => isset($data['creation_date']) ? $data['creation_date'] : date('Y-m-d'),
					'type' => $type,
				];

				$db->insert('lot_wise_rm', $insert);
				return $db->insert_id();
		}
	}
}