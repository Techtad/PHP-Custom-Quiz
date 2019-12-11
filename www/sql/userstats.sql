SELECT `user`, SUM(`correct`) as `right`, (COUNT(*) - SUM(`correct`)) as `wrong`, (SUM(`correct`) / COUNT(*) * 100) as `percent`
FROM `scores`
GROUP BY `user`
ORDER BY `percent` DESC
LIMIT 10 OFFSET %d;