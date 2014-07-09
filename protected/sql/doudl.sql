SELECT i.id,o.id,o.type_id,a.name,o.account_id, os.name FROM forbac_openvpn.order_server_action_ids i

JOIN (SELECT count(*) as co, id FROM forbac_openvpn.order_server_action_ids group by orderID, serverID, actionID Having co > 1)
 ii ON ii.id = i.id
Left JOIN orders o ON o.id = i.orderID

Left JOIN actions os ON os.id = o.action_id
Left JOIN users u ON u.id=o.user_id
Left JOIN periods pi ON pi.id=o.period_id
Left JOIN types t ON t.id = o.type_id
Left JOIN protocols p ON p.id = o.protocol_id
Left JOIN accounts a ON a.id = o.account_id

;