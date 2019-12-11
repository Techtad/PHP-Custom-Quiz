SELECT `user`, SUM(`correct`) as `right`, (COUNT(*) - SUM(`correct`)) as `wrong`, (SUM(`correct`) / COUNT(*) * 100) as `percent`
FROM `scores`
WHERE (SELECT `quiz_id` FROM `questions` WHERE `id` = `question_id`) = %s
GROUP BY `user`
ORDER BY `right` DESC
LIMIT %d, 10;