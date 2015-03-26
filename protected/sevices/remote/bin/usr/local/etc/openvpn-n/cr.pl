#!/usr/bin/perl

# Tool for create the database of vpn accounts.

$subnet=shift || 5;
$userdir="ccd";
$password_file="password";
# Note: X - it's 192.168.X.0/24
$i=5;
while ($i < 254) {
    $user=usergen(5);
    open(CCD,">$userdir/$user");
    $i2=$i+1;
    $str="ifconfig-push 192.168.$subnet.$i 192.168.$subnet.$i2\n";
    print CCD $str;
    close(CCD);
    open(PASS,">>$password_file");
    print PASS "$user:L\n";
    $i+=4;
}

sub usergen {
    my($digits)=@_;
    my $count=1;
    $passwd='';
        while ($count <= $digits) {
        $salt=int(rand(2));
        if ($salt eq 0) {
            $symb=48+int(rand(9));
        } else {
            $symb=97+int(rand(9));
        }
        $one=chr($symb);
        $passwd.=$one;
        $count++;
    }
    return $passwd;
}

