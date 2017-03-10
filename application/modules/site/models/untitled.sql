
SELECT
	* 
FROM
	(
		SELECT
			s.*, @rank:=@rank + 1 AS rank 
		FROM
			(
				SELECT
					`usuarios`.`nome`, `usuarios`.`apelido`, `perfis`.`slug` AS `perfil_slug`, `usuario`, sum(pontuacao) AS pontuacao_total 
				FROM
					`vendas_usuarios` `t`
				INNER JOIN
					`vendas` ON `t`.`venda` = `vendas`.`id` 
				INNER JOIN
					`usuarios` ON `t`.`usuario` = `usuarios`.`id` 
				INNER JOIN
					`perfis` ON `usuarios`.`perfil` = `perfis`.`id` 
				WHERE
					MONTH(data_contrato) = '12' AND YEAR(data_contrato) = '2016'
				GROUP BY
					`usuario`
			) AS s,

			(
				SELECT
					@rank := 0
			) AS init 
		ORDER BY
			`pontuacao_total` DESC
	) AS r

