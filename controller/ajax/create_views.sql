-- view_system_vuls

CREATE VIEW view_system_vuls AS
SELECT
	*
FROM
    scan_stats
WHERE
	scan_stats.oid LIKE '2.16.886.101.90028.20002%' -- just show tainan gov 
