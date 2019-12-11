SELECT
	(SELECT `name` FROM `quizzes` WHERE `id` = (SELECT `quiz_id` FROM `questions` WHERE `id` = `question_id`)) as `quiz`,
	(SELECT `question` FROM `questions` WHERE `id` = `question_id`) as `question`,
    SUM(`correct`) as `right`,
    (COUNT(*) - SUM(`correct`)) as `wrong`,
    (SUM(`correct`) / COUNT(*) * 100) as `percent`
FROM `scores`
GROUP BY `question_id`
ORDER BY `wrong` DESC
LIMIT 10 OFFSET %d;