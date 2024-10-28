<?php
class DaybookModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getPurchase($filterParams)
	{
		$filterParams = $this->security->xss_clean($filterParams);

		$fromDate = $this->db->escape($filterParams['fromDate']);
		$toDate = $this->db->escape($filterParams['toDate']);

		$searchQuery = " TRUE AND "; // Starting where clause
		if (!empty($filterParams['fromDate']) && $filterParams['fromDate'] !== NULL && !empty($filterParams['fromDate']))
			$searchQuery .= " DATE(pu.date) >= DATE($fromDate) AND "; // Add from date condition
		if (!empty($filterParams['toDate']) && $filterParams['toDate'] !== NULL && !empty($filterParams['fromDate']))
			$searchQuery .= " DATE(pu.date) <= DATE($toDate) AND "; // Add to date condition
		$searchQuery .= " TRUE "; // Ending where clause

		$rowPurchase = "SELECT
		                pu.id,
		                pu.code,
                        SUM(pud.fine) as fine,
                        SUM(pud.sub_total) as sub_total,
                        SUM(pud.net_weight) as net_weight
                        FROM purchase_detail pud
                        LEFT JOIN purchase pu ON pu.id = pud.purchase_id
                        WHERE $searchQuery
                        group by pu.id";

		return $this->db->query($rowPurchase)->result_array();
	}

	public function getPurchaseReturn($filterParams)
	{
		$filterParams = $this->security->xss_clean($filterParams);

		$fromDate = $this->db->escape($filterParams['fromDate']);
		$toDate = $this->db->escape($filterParams['toDate']);

		$searchQuery = " TRUE AND "; // Starting where clause
		if (!empty($filterParams['fromDate']) && $filterParams['fromDate'] !== NULL && !empty($filterParams['fromDate']))
			$searchQuery .= " DATE(pur.date) >= DATE($fromDate) AND "; // Add from date condition
		if (!empty($filterParams['toDate']) && $filterParams['toDate'] !== NULL && !empty($filterParams['fromDate']))
			$searchQuery .= " DATE(pur.date) <= DATE($toDate) AND "; // Add to date condition
		$searchQuery .= " TRUE "; // Ending where clause

		$rowPurchaseReturn = "SELECT
		                pur.id,
		                pur.code,
                        SUM(purd.fine) as fine,
                        SUM(purd.sub_total) as sub_total,
                        SUM(purd.net_weight) as net_weight
                        FROM purchase_return_detail purd
                        LEFT JOIN purchase_return pur ON pur.id = purd.purchase_id
                        WHERE $searchQuery
                        group by pur.id";

		return $this->db->query($rowPurchaseReturn)->result_array();
	}

	public function getSale($filterParams)
	{
		$filterParams = $this->security->xss_clean($filterParams);

		$fromDate = $this->db->escape($filterParams['fromDate']);
		$toDate = $this->db->escape($filterParams['toDate']);

		$searchQuery = " TRUE AND "; // Starting where clause
		if (!empty($filterParams['fromDate']) && $filterParams['fromDate'] !== NULL && !empty($filterParams['fromDate']))
			$searchQuery .= " DATE(sl.date) >= DATE($fromDate) AND "; // Add from date condition
		if (!empty($filterParams['toDate']) && $filterParams['toDate'] !== NULL && !empty($filterParams['fromDate']))
			$searchQuery .= " DATE(sl.date) <= DATE($toDate) AND "; // Add to date condition
		$searchQuery .= " TRUE "; // Ending where clause

		$rowSale = "SELECT
		                sl.id,
		                sl.code,
                        SUM(sd.fine) as fine,
                        SUM(sd.sub_total) as sub_total,
                        SUM(sd.net_weight) as net_weight
                        FROM sale_detail sd
                        LEFT JOIN sale sl ON sl.id = sd.sale_id
                        WHERE $searchQuery
                        group by sl.id";

		return $this->db->query($rowSale)->result_array();
	}

	public function getSaleReturn($filterParams)
	{
		$filterParams = $this->security->xss_clean($filterParams);

		$fromDate = $this->db->escape($filterParams['fromDate']);
		$toDate = $this->db->escape($filterParams['toDate']);

		$searchQuery = " TRUE AND "; // Starting where clause
		if (!empty($filterParams['fromDate']) && $filterParams['fromDate'] !== NULL && !empty($filterParams['fromDate']))
			$searchQuery .= " DATE(sr.date) >= DATE($fromDate) AND "; // Add from date condition
		if (!empty($filterParams['toDate']) && $filterParams['toDate'] !== NULL && !empty($filterParams['fromDate']))
			$searchQuery .= " DATE(sr.date) <= DATE($toDate) AND "; // Add to date condition
		$searchQuery .= " TRUE "; // Ending where clause

		$rowSaleReturn = "SELECT
	                    sr.id,
	                    sr.code,
                        SUM(srd.fine) as fine,
                        SUM(srd.sub_total) as sub_total,
                        SUM(srd.net_weight) as net_weight
                        FROM sale_return_detail srd
                        LEFT JOIN sale_return sr ON sr.id = srd.sale_id
                        WHERE $searchQuery
                        group by sr.id";

		return $this->db->query($rowSaleReturn)->result_array();
	}


	public function processData($filterParams, $processes)
	{
		$data = [];
		$filterParams = $this->security->xss_clean($filterParams);
		$fromDate = $this->db->escape_str($filterParams['fromDate']);
		$toDate = $this->db->escape_str($filterParams['toDate']);


		foreach ($processes as $key => $process) {
			$process_id = $process['id'];
			$data[$key] = [
				'givenData' => [],
				'receiveData' => [],
				'process_name' => $process['name']
			];

			// "Given" data query
			$givenData = "SELECT
                            G.row_material_weight,
                            G.given_weight,
                            G.given_qty,
                            G.total_weight as total_weight,
                            C.name as worker_name,
                            P.name as process_name
                        FROM given G
                        LEFT JOIN customer C ON C.id = G.worker_id
                        LEFT JOIN process P ON P.id = G.process_id
                        WHERE G.creation_date >= DATE('$fromDate')
                        AND G.creation_date <= DATE('$toDate')
                        AND G.process_id = $process_id
                        GROUP BY G.id";
			$data[$key]['givenData'] = $this->db->query($givenData)->result_array();

			// "Receive" data query
			$receiveData = "SELECT
                            R.pcs as qty,
                            R.weight as weight,
                            R.total_weight,
                            R.row_material_weight,
                            R.touch,
                            R.fine,
                            C.name as worker_name,
                            P.name as process_name
                        FROM receive R
                        LEFT JOIN given G ON G.id = R.given_id
                        LEFT JOIN customer C ON C.id = G.worker_id
                        LEFT JOIN process P ON P.id = G.process_id
                        WHERE R.creation_date >= DATE('$fromDate')
                        AND R.creation_date <= DATE('$toDate')
                        AND G.process_id = $process_id
                        GROUP BY R.id";

			$data[$key]['receiveData'] = $this->db->query($receiveData)->result_array();
		}

		return $data;
	}
}
