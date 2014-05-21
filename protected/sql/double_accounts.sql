SELECT  o.id, of.config,os.action,
                       p.name as protocol,
                       pg.name as type,
                       o.os,
                       o.portable,
					  count(a.account) as co,
                       a.account, a.pass,
                       s.name as server,s.hostname,s.port,s.ip
			
        FROM orders o
                Join types pg On pg.id=o.type_id
                Join protocols p On p.id=o.protocol_id
                Join actions os On os.id=o.action_id 
                Join order_server_ids osids On osids.orderID = o.id
                Join servers s On s.id = osids.serverID
                Join (SELECT a.id, a.name as account,a.pass     
					FROM accounts a Where a.name in (        SELECT name FROM accounts a Group by name having count(name) >1       )         
				) a On a.id=o.account_id   
                Left Join order_configs of On of.order_id = o.id
		-- Where os.action = "unlock" 
			-- and o.id = 887

			 -- and of.config IS NULL

		      -- Where a.account IS NOT NULL
	Group by a.account
	 having count(a.account) >1
	Order by co desc
	 