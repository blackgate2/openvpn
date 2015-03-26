#!/usr/bin/perl

use Digest::MD5 qw(md5_hex);
$|++;
$base='/usr/local/etc/tcp-n/password';
$username = $ENV{"username"};
$password = $ENV{"password"};
$true = 1;

open(BASE,$base);
while(<BASE>) {
    if($_=~/^$username\:/) {
        ~s/\r|\n//g;
        ($u,$p)=split(":",$_);
        $chpass=md5_hex($password);
        $true = 0
            if ($chpass eq $p);
    }
}
close(BASE);
if (!$true and $username=~/^CORP/) {
chomp($server=`hostname`);

use IO::Socket;

$web=IO::Socket::INET->new(PeerAddr=>"confs.openvpn.ru", PeerPort=>80) || die "error\n";
$web->autoflush(1);
print $web qq(GET /auth/$username HTTP/1.0
Host: confs.openvpn.ru
Authorization: Basic c3VwcG9ydDpUcDBWa2VTYzQ=
User-Agent: Mozilla/4.0
Content-Type: Text
Connection: Close


qq);

$answer=<$web>;

if ($answer=~/HTTP\/1.1 404 Not Found/) {
$true = 0;
} else {

chomp(@answer=<$web>);
$ans=$answer[scalar(@answer)-1];
if ($ans=~/$server/) { exit 0  } else { exit 1 }

}


close($web);
$web=IO::Socket::INET->new(PeerAddr=>"confs.openvpn.ru", PeerPort=>80) || die "error\n";
$web->autoflush(1);

print $web qq(GET /auth/touch.cgi?file=$username&server=$server HTTP/1.0
Host: confs.openvpn.ru
Authorization: Basic c3VwcG9ydDpUcDBWa2VTYzQ=
User-Agent: Mozilla/4.0
Content-Type: text/html


qq);

close($web);

}
exit $true;
