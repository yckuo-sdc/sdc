-- view_system_vuls
CREATE VIEW view_system_vuls AS
SELECT
    oid,
    ou,
    system_name,
    living,
    SUM(total_VUL) AS total_VUL,
    SUM(fixed_VUL) AS fixed_VUL,
    SUM(total_high_VUL) AS total_high_VUL,
    SUM(fixed_high_VUL) AS fixed_high_VUL,
    SUM(overdue_high_VUL) AS overdue_high_VUL,
    SUM(overdue_medium_VUL) AS overdue_medium_VUL
FROM
    (
		SELECT
			oid,
			ou,
			system_name,
			1 AS living,
			'0' AS total_VUL,
			'0' AS fixed_VUL,
			'0' AS total_high_VUL,
			'0' AS fixed_high_VUL,
			'0' AS overdue_high_VUL,
			'0' AS overdue_medium_VUL
		FROM
			scan_targets
		UNION ALL
		SELECT
			oid,
			ou,
			system_name,
			(
				SELECT count(*) FROM scan_targets
				WHERE INSTR(CONCAT(',', GROUP_CONCAT(distinct scan_results.ip), ','), CONCAT(',', ip, ','))
			) > 0 AS living,
			COUNT(system_name) AS total_VUL,
			SUM(
				CASE WHEN(status IN('已修補', '豁免', '誤判')) THEN 1 ELSE 0
				END
			) AS fixed_VUL,
			SUM(
				CASE WHEN(severity IN('High', 'Critical')) THEN 1 ELSE 0
				END
			) AS total_high_VUL,
			SUM(
				CASE WHEN(
					severity IN('High', 'Critical')
					AND
					status IN('已修補', '豁免', '誤判')
				) THEN 1 ELSE 0
				END
			) AS fixed_high_VUL,
			SUM(
				CASE WHEN(
					severity IN('High', 'Critical')
					AND
					status NOT IN('已修補', '豁免', '誤判')
					AND
					scan_date <(NOW() - INTERVAL 1 MONTH)
				) THEN 1 ELSE 0
				END
			) AS overdue_high_VUL,
			SUM(
				CASE WHEN(
					severity = 'Medium'
					AND
					status NOT IN('已修補', '豁免', '誤判')
					AND
					scan_date <(NOW() - INTERVAL 2 MONTH)
				) THEN 1 ELSE 0
				END
			) AS overdue_medium_VUL_VUL
		FROM
			scan_results
		GROUP BY
			oid,
			ou,
			system_name
	) B
WHERE 
    B.oid LIKE '2.16.886.101.90028.20002%' -- just show tainan gov
GROUP BY
	B.oid,
	B.ou,
	B.system_name,
	B.living
ORDER BY
	B.oid,
	B.living DESC,
	B.system_name
