#!/usr/bin/perl

system ("grep -v closed /usr/apache/htdocs/openvpn.ru/confs/confs2/pass|grep -v agen > /usr/apache/htdocs/openvpn.ru/confs/confs2/pass2");
system("mv /usr/apache/htdocs/openvpn.ru/confs/confs2/pass2 /usr/apache/htdocs/openvpn.ru/confs/confs2/pass"); 