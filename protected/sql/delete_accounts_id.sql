-- SELECT * FROM accounts 
-- Where id not in (SELECT accountID FROM order_server_action_ids)
-- INSERT INTO tmp (i)
-- SELECT o.action_id FROM orders o
-- JOIN users u ON u.id=o.user_id
            -- JOIN periods pi ON pi.id=o.period_id
            -- JOIN types t ON t.id = o.type_id
            -- JOIN protocols p ON p.id = o.protocol_id
            -- JOIN actions os ON os.id = o.action_id
	-- LEFT JOIN accounts os ON os.id = o.action_id
-- Where o.account_id not in (SELECT id FROM accounts)
-- SELECT account_id FROM orders

 -- INSERT INTO tmp (i)
 -- SELECT id FROM accounts 
 -- Where id  not in  ( SELECT o.account_id FROM orders o
 -- JOIN accounts os ON os.id = o.account_id);
SET SQL_SAFE_UPDATES=0;
delete from accounts where id in (select i from tmp);

delete FROM forbac_openvpn.order_server_action_ids where accountID in (select i from tmp) 
-- JOIN  tmp t ON t.i = o.accountID;
