<?php
class stargazer_model extends CI_Model
{
    function rev($deg, $n)
    {
        while($deg < 0 || $deg > $n)
        {
            if($deg > $n)
                $deg = $deg - $n;
            else if($deg < 0)
                $deg = $deg + $n;
        }
        return $deg;
    }

    function rev2($deg, $max, $min, $change)
    {
        while($deg < $min || $deg > $max)
        {
            if($deg > $max)
                $deg = $deg - $change;
            else if($deg < $min)
                $deg = $deg + $change;
        }
        return $deg;
    }

    function calcE($E0, $E1, $e, $M)
    {
        $diff = abs($E0 - $E1);
        if($diff <= 0.005)
            return;
        else
        {
            $E0 = $E1;
            $E1 =  $E0 - ($E0 - (180/M_PI) * $e * sin(deg2rad($E0)) - $M) / (1 - $e * cos(deg2rad($E0)));
            $this->calcE($E0, $E1, $e, $M);
        }
    }

    function calcValue($data, $d)
    {
        if(count($data) == 1)
            return (float) $data[0];
        if($data[1] == '+')
            return $data[0] + $data[2] * $d;
        if($data[1] == '-')
            return $data[0] - $data[2] * $d;
    }

    function initializePlanet($planet, $d)
    {
        $N = explode(";",$planet->N);
        $planet->N = $this->rev($this->calcValue($N, $d), 360);
        $i = explode(";",$planet->i);
        $planet->i = $this->rev($this->calcValue($i, $d), 360);
        $w = explode(";",$planet->w);
        $planet->w = $this->rev($this->calcValue($w, $d), 360);
        $a = explode(";",$planet->a);
        $planet->a = $this->calcValue($a, $d);
        $e = explode(";",$planet->e);
        $planet->e = $this->calcValue($e, $d);
        $M = explode(";",$planet->M);
        $planet->M = $this->rev($this->calcValue($M, $d), 360);
    }

    function calcRADECLSun($sun, $d, $oblecl)
    {
        $sun->L = $this->rev($sun->w + $sun->M, 360);
        $E = $sun->M + (180/M_PI) * $sun->e * sin(deg2rad($sun->M)) * (1 + $sun->e * cos(deg2rad($sun->M)));

        $x = cos(deg2rad($E)) - $sun->e;
        $y = sin(deg2rad($E)) * sqrt(1 - $sun->e*$sun->e);

        $r = sqrt($x*$x + $y*$y);
        $v = rad2deg(atan2( $y, $x ));

        $lon = $v + $sun->w;
        $lon = $this->rev($lon, 360);

        $sun->xs = $r * cos(deg2rad($lon));
        $sun->ys = $r * sin(deg2rad($lon));
        $zs = 0.0;

        $xequat = $sun->xs;
        $yequat = $sun->ys * cos(deg2rad($oblecl)) - $zs * sin(deg2rad($oblecl));
        $zequat = $sun->ys * sin(deg2rad($oblecl)) + $zs * cos(deg2rad($oblecl));

        $sun->RA   =  rad2deg(atan2( $yequat, $xequat ));
        $sun->Decl =  rad2deg(atan2( $zequat, sqrt( $xequat*$xequat + $yequat*$yequat) ));
        $sun->R    =  sqrt( $xequat*$xequat + $yequat*$yequat + $zequat*$zequat );
    }

    function MoonPerturbationLongitude($Mm, $Ms, $D, $F)
    {
        $pert = - 1.274 * sin(deg2rad($Mm - 2*$D));
        $pert += + 0.658 * sin(deg2rad(2*$D));
        $pert += - 0.186 * sin(deg2rad($Ms));
        $pert += - 0.059 * sin(deg2rad(2*$Mm - 2*$D));
        $pert += - 0.057 * sin(deg2rad($Mm - 2*$D + $Ms));
        $pert += + 0.053 * sin(deg2rad($Mm + 2*$D));
        $pert += + 0.046 * sin(deg2rad(2*$D - $Ms));
        $pert += + 0.041 * sin(deg2rad($Mm - $Ms));
        $pert += - 0.035 * sin(deg2rad($D));
        $pert += - 0.031 * sin(deg2rad($Mm + $Ms));
        $pert += - 0.015 * sin(deg2rad(2*$F - 2*$D));
        $pert += + 0.011 * sin(deg2rad($Mm - 4*$D));
        return $pert;
    }

    function MoonPerturbationLatitude($Mm, $F, $D)
    {
        $pert = - 0.173 * sin(deg2rad($F - 2*$D));
        $pert += - 0.055 * sin(deg2rad($Mm - $F - 2*$D));
        $pert += - 0.046 * sin(deg2rad($Mm + $F - 2*$D));
        $pert += + 0.033 * sin(deg2rad($F + 2*$D));
        $pert += + 0.017 * sin(deg2rad(2*$Mm + $F));
        return $pert;
    }

    function MoonPerturbationDistance($Mm, $D)
    {
        $pert = - 0.58 * cos(deg2rad($Mm - 2*$D));
        $pert += - 0.46 * cos(deg2rad(2*$D));
        return $pert;
    }

    function JupiterPerturbationLongitude($Mj, $Ms)
    {
        $pert = -0.332  * sin(deg2rad(2* $Mj - 5* $Ms - 67.6));
        $pert += -0.056  * sin(deg2rad(2* $Mj - 2* $Ms + 21));
        $pert += +0.042  * sin(deg2rad(3* $Mj - 5* $Ms + 21));
        $pert += -0.036  * sin(deg2rad( $Mj - 2* $Ms));
        $pert += +0.022  * cos(deg2rad( $Mj -  $Ms));
        $pert += +0.023  * sin(deg2rad(2* $Mj - 3* $Ms + 52));
        $pert += -0.016  * sin(deg2rad( $Mj - 5* $Ms - 69));
        return $pert;
    }

    function SaturnPerturbationLongitude($Ms, $Mj)
    {
        $pert = +0.812  * sin(deg2rad(2*$Mj - 5*$Ms - 67.6 ));
        $pert += -0.229  * cos(deg2rad(2*$Mj - 4*$Ms - 2 ));
        $pert += +0.119  * sin(deg2rad($Mj - 2*$Ms - 3 ));
        $pert += +0.046  * sin(deg2rad(2*$Mj - 6*$Ms - 69 ));
        $pert += +0.014  * sin(deg2rad($Mj - 3*$Ms + 32 ));
        return $pert;
    }

    function SaturnPerturbationLatitude($Ms, $Mj)
    {
        $pert = -0.020 * cos(deg2rad(2*$Mj - 4*$Ms - 2));
        $pert += +0.018 * sin(deg2rad(2*$Mj - 6*$Ms - 49));
        return $pert;
    }

    function UranusPerturbationLongitude($Mj, $Ms, $Mu)
    {
        $pert = +0.040 * sin(deg2rad($Ms - 2*$Mu + 6));
        $pert += +0.035 * sin(deg2rad($Ms - 3*$Mu + 33));
        $pert += -0.015 * sin(deg2rad($Mj - $Mu + 20));
        return $pert;
    }

    function calcRADECLMoon($moon, $d, $sun, $oblecl)
    {
        $E0 = $moon->M + (180/M_PI) * $moon->e * sin(deg2rad($moon->M)) * (1 + $moon->e * cos(deg2rad($moon->M)));
        $E1 = $E0 - ($E0 - (180/M_PI) * $moon->e * sin(deg2rad($E0)) - $moon->M) / (1 - $moon->e * cos(deg2rad($E0)));
        $this->calcE($E0,$E1, $moon->e, $moon->M);
        $E = $E1;

        $x = $moon->a * (cos(deg2rad($E)) - $moon->e);
        $y = $moon->a * sqrt(1 - $moon->e*$moon->e) * sin(deg2rad($E));

        $r = sqrt($x*$x + $y*$y);
        $v = $this->rev(rad2deg(atan2( $y, $x )), 360);

        $xeclip = $r * ( cos(deg2rad($moon->N)) * cos(deg2rad($v+$moon->w)) - sin(deg2rad($moon->N)) * sin(deg2rad($v+$moon->w)) * cos(deg2rad($moon->i)) );
        $yeclip = $r * ( sin(deg2rad($moon->N)) * cos(deg2rad($v+$moon->w)) + cos(deg2rad($moon->N)) * sin(deg2rad($v+$moon->w)) * cos(deg2rad($moon->i)) );
        $zeclip = $r * sin(deg2rad($v+$moon->w)) * sin(deg2rad($moon->i));

        $Lm = $moon->N + $moon->w + $moon->M;
        $D = $Lm - $sun->L;
        $F = $Lm - $moon->N;
        $longPert = $this->MoonPerturbationLongitude($moon->M, $sun->M, $D, $F);
        $long =  $this->rev(rad2deg(atan2( $yeclip, $xeclip )) + $longPert, 360);
        $latPert = $this->MoonPerturbationLatitude($moon->M, $F, $D);
        $lat  =  rad2deg(atan2( $zeclip, sqrt( $xeclip*$xeclip + $yeclip*$yeclip ) )) + $latPert;
        $rPert = $this->MoonPerturbationDistance($moon->M, $D);
        $r    =  sqrt( $xeclip*$xeclip + $yeclip*$yeclip + $zeclip*$zeclip ) + $rPert;

        $xeclip = cos(deg2rad($long)) * cos(deg2rad($lat));
        $yeclip = sin(deg2rad($long)) * cos(deg2rad($lat));
        $zeclip = sin(deg2rad($lat));

        $xequat = $xeclip;
        $yequat = $yeclip * cos(deg2rad($oblecl)) - $zeclip * sin(deg2rad($oblecl));
        $zequat = $yeclip * sin(deg2rad($oblecl)) + $zeclip * cos(deg2rad($oblecl));
        $moon->RA = $this->rev(rad2deg(atan2( $yequat, $xequat )), 360);
        $moon->Decl = rad2deg(atan2( $zequat, sqrt( $xequat*$xequat + $yequat*$yequat) ));
        $moon->R = $r;
    }

    function calcRADECLPlanet($planet, $d, $sun, $oblecl)
    {
        $E0 = $planet->M + (180/M_PI) * $planet->e * sin(deg2rad($planet->M)) * (1 + $planet->e * cos(deg2rad($planet->M)));
        $E1 = $E0 - ($E0 - (180/M_PI) * $planet->e * sin(deg2rad($E0)) - $planet->M) / (1 - $planet->e * cos(deg2rad($E0)));
        $this->calcE($E0,$E1, $planet->e, $planet->M);
        $E = $E1;

        $recx = $planet->a * (cos(deg2rad($E)) - $planet->e);
        $recy = $planet->a * sqrt(1 - $planet->e*$planet->e) * sin(deg2rad($E));
        
        $r = sqrt($recx*$recx + $recy*$recy);
        $v = $this->rev(rad2deg(atan2( $recy, $recx )), 360);

        $xeclip = $r * ( cos(deg2rad($planet->N)) * cos(deg2rad($v+$planet->w)) - sin(deg2rad($planet->N)) * sin(deg2rad($v+$planet->w)) * cos(deg2rad($planet->i)) );
        $yeclip = $r * ( sin(deg2rad($planet->N)) * cos(deg2rad($v+$planet->w)) + cos(deg2rad($planet->N)) * sin(deg2rad($v+$planet->w)) * cos(deg2rad($planet->i)) );
        $zeclip = $r * sin(deg2rad($v+$planet->w)) * sin(deg2rad($planet->i));

        $long = $this->rev(rad2deg(atan2( $yeclip, $xeclip )), 360);
        $lat  =  rad2deg(atan2( $zeclip, sqrt( $xeclip*$xeclip + $yeclip*$yeclip ) ));
        $r    =  sqrt( $xeclip*$xeclip + $yeclip*$yeclip + $zeclip*$zeclip );

        $xh = $r * cos(deg2rad($long)) * cos(deg2rad($lat));
        $yh = $r * sin(deg2rad($long)) * cos(deg2rad($lat));
        $zh = $r * sin(deg2rad($lat));

        $xg = $xh + $sun->xs; //xs del sole
        $yg = $yh + $sun->ys; //ys del sole
        $zg = $zh;

        $xe = $xg;
        $ye = $yg * cos(deg2rad($oblecl)) - $zg * sin(deg2rad($oblecl));
        $ze = $yg * sin(deg2rad($oblecl)) + $zg * cos(deg2rad($oblecl));

        $planet->RA = $this->rev(rad2deg(atan2( $ye, $xe )), 360);
        $planet->Decl = rad2deg(atan2( $ze, sqrt( $xe*$xe + $ye*$ye) ));
        $planet->R = sqrt($xe*$xe+$ye*$ye+$ze*$ze);
    }

    function calcRADECLJupiterSaturn($planet1, $d, $planet2, $oblecl, $sun)
    {
        $E0 = $planet1->M + (180/M_PI) * $planet1->e * sin(deg2rad($planet1->M)) * (1 + $planet1->e * cos(deg2rad($planet1->M)));
        $E1 = $E0 - ($E0 - (180/M_PI) * $planet1->e * sin(deg2rad($E0)) - $planet1->M) / (1 - $planet1->e * cos(deg2rad($E0)));
        $this->calcE($E0,$E1, $planet1->e, $planet1->M);
        $E = $E1;

        $recx = $planet1->a * (cos(deg2rad($E)) - $planet1->e);
        $recy = $planet1->a * sqrt(1 - $planet1->e*$planet1->e) * sin(deg2rad($E));
        
        $r = sqrt($recx*$recx + $recy*$recy);
        $v = $this->rev(rad2deg(atan2( $recy, $recx )), 360);

        $xeclip = $r * ( cos(deg2rad($planet1->N)) * cos(deg2rad($v+$planet1->w)) - sin(deg2rad($planet1->N)) * sin(deg2rad($v+$planet1->w)) * cos(deg2rad($planet1->i)) );
        $yeclip = $r * ( sin(deg2rad($planet1->N)) * cos(deg2rad($v+$planet1->w)) + cos(deg2rad($planet1->N)) * sin(deg2rad($v+$planet1->w)) * cos(deg2rad($planet1->i)) );
        $zeclip = $r * sin(deg2rad($v+$planet1->w)) * sin(deg2rad($planet1->i));

        $pertLong = 0;
        $pertLat = 0;
        if($planet1->Name == "Jupiter")
            $pertLong = $this->JupiterPerturbationLongitude($planet1->M, $planet2->M);
        else
        {
            $pertLong = $this->SaturnPerturbationLongitude($planet1->M, $planet2->M);
            $pertLat = $this->SaturnPerturbationLatitude($planet1->M, $planet2->M);
        }

        $long = $this->rev(rad2deg(atan2( $yeclip, $xeclip )) + $pertLong, 360);
        $lat  =  rad2deg(atan2( $zeclip, sqrt( $xeclip*$xeclip + $yeclip*$yeclip ) )) + $pertLat;
        $r    =  sqrt( $xeclip*$xeclip + $yeclip*$yeclip + $zeclip*$zeclip );

        $xh = $r * cos(deg2rad($long)) * cos(deg2rad($lat));
        $yh = $r * sin(deg2rad($long)) * cos(deg2rad($lat));
        $zh = $r * sin(deg2rad($lat));

        $xg = $xh + $sun->xs; //xs del sole
        $yg = $yh + $sun->ys; //ys del sole
        $zg = $zh;

        $xe = $xg;
        $ye = $yg * cos(deg2rad($oblecl)) - $zg * sin(deg2rad($oblecl));
        $ze = $yg * sin(deg2rad($oblecl)) + $zg * cos(deg2rad($oblecl));

        $planet1->RA = $this->rev(rad2deg(atan2( $ye, $xe )), 360);
        $planet1->Decl = rad2deg(atan2( $ze, sqrt( $xe*$xe + $ye*$ye) ));
        $planet1->R = sqrt($xe*$xe+$ye*$ye+$ze*$ze);
    }

    function calcRADECLUranus($planet, $d, $jupiter, $saturn, $oblecl, $sun)
    {
        $E0 = $planet->M + (180/M_PI) * $planet->e * sin(deg2rad($planet->M)) * (1 + $planet->e * cos(deg2rad($planet->M)));
        $E1 = $E0 - ($E0 - (180/M_PI) * $planet->e * sin(deg2rad($E0)) - $planet->M) / (1 - $planet->e * cos(deg2rad($E0)));
        $this->calcE($E0,$E1, $planet->e, $planet->M);
        $E = $E1;

        $recx = $planet->a * (cos(deg2rad($E)) - $planet->e);
        $recy = $planet->a * sqrt(1 - $planet->e*$planet->e) * sin(deg2rad($E));
        
        $r = sqrt($recx*$recx + $recy*$recy);
        $v = $this->rev(rad2deg(atan2( $recy, $recx )), 360);

        $xeclip = $r * ( cos(deg2rad($planet->N)) * cos(deg2rad($v+$planet->w)) - sin(deg2rad($planet->N)) * sin(deg2rad($v+$planet->w)) * cos(deg2rad($planet->i)) );
        $yeclip = $r * ( sin(deg2rad($planet->N)) * cos(deg2rad($v+$planet->w)) + cos(deg2rad($planet->N)) * sin(deg2rad($v+$planet->w)) * cos(deg2rad($planet->i)) );
        $zeclip = $r * sin(deg2rad($v+$planet->w)) * sin(deg2rad($planet->i));

        $pertLong = $this->UranusPerturbationLongitude($jupiter->M, $saturn->M, $planet->M);

        $long = $this->rev(rad2deg(atan2( $yeclip, $xeclip )) + $pertLong, 360);
        $lat  =  rad2deg(atan2( $zeclip, sqrt( $xeclip*$xeclip + $yeclip*$yeclip ) ));
        $r    =  sqrt( $xeclip*$xeclip + $yeclip*$yeclip + $zeclip*$zeclip );

        $xh = $r * cos(deg2rad($long)) * cos(deg2rad($lat));
        $yh = $r * sin(deg2rad($long)) * cos(deg2rad($lat));
        $zh = $r * sin(deg2rad($lat));

        $xg = $xh + $sun->xs; //xs del sole
        $yg = $yh + $sun->ys; //ys del sole
        $zg = $zh;

        $xe = $xg;
        $ye = $yg * cos(deg2rad($oblecl)) - $zg * sin(deg2rad($oblecl));
        $ze = $yg * sin(deg2rad($oblecl)) + $zg * cos(deg2rad($oblecl));

        $planet->RA = $this->rev(rad2deg(atan2( $ye, $xe )), 360);
        $planet->Decl = rad2deg(atan2( $ze, sqrt( $xe*$xe + $ye*$ye) ));
        $planet->R = sqrt($xe*$xe+$ye*$ye+$ze*$ze);
    }

    function getRADECL($d)
    {
        $query = $this->db->query("SELECT * from planets;");
        $data = $query->result();

        $oblecl = 23.4393 - 3.563E-7 * $d;
        foreach ($data as &$planet)
        {
            $this->initializePlanet($planet, $d);
        }
        foreach ($data as &$planet) 
        {
            if($planet->Name == "Sun")
                $this->calcRADECLSun($planet, $d, $oblecl);
            else if($planet->Name == "Moon")
                $this->calcRADECLMoon($planet, $d, $data[0], $oblecl);
            else if($planet->Name == "Jupiter")
                $this->calcRADECLJupiterSaturn($planet, $d, $data[6], $oblecl, $data[0]);
            else if($planet->Name == "Saturn")
                $this->calcRADECLJupiterSaturn($planet, $d, $data[5], $oblecl, $data[0]);
            else if($planet->Name == "Uranus")
                $this->calcRADECLUranus($planet, $d, $data[5], $data[6], $oblecl, $data[0]);
            else
                $this->calcRADECLPlanet($planet, $d, $data[0], $oblecl);
        }
        return $data;
    }

    function calcAA($planet, $SIDTIME)
    {
        $HA = ($SIDTIME * 15) - $planet->RA;
        $HA = $this->rev($HA, 360);

        $xc = cos(deg2rad($HA)) * cos(deg2rad($planet->Decl));
        $yc = sin(deg2rad($HA)) * cos(deg2rad($planet->Decl));
        $zc = sin(deg2rad($planet->Decl));

        $xhor = $xc * sin(deg2rad($this->session->lat)) - $zc * cos(deg2rad($this->session->lat));
        $yhor = $yc;
        $zhor = $xc * cos(deg2rad($this->session->lat)) + $zc * sin(deg2rad($this->session->lat));

        $planet->azimuth  = rad2deg(atan2( $yhor, $xhor )) + 180;
        $planet->altitude = rad2deg(asin( $zhor ));
    }

    function getAA($planets)
    {
        $Ls = $planets[0]->L;
        $GMST0 = $Ls/15 + 12; 
        $SIDTIME = $GMST0 + $this->session->hUT + $this->session->long/15; //SIDTIME = GMST0 + UT + LON/15
        $SIDTIME = $this->rev($SIDTIME, 24);
        foreach ($planets as &$planet)
        {
            $this->calcAA($planet, $SIDTIME);
        }
    }

    function format($planets)
    {
        foreach ($planets as &$planet)
        {
            $planet->RA = $this->dec2time($planet->RA / 15);
            $planet->Decl = $this->dec2deg($planet->Decl);
            $planet->azimuth = $this->dec2degAz($planet->azimuth);
            $planet->altitude = $this->dec2deg($planet->altitude);
        }
    }

    function dec2deg($dec)
    {
        $seconds = round($dec * 3600);
        $degrees = floor($dec);
        $seconds -= $degrees * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        return $degrees.'° '.$minutes."' ".$seconds."''";
    }

    function dec2degAz($dec)
    {
        $seconds = round($dec * 3600);
        $degrees = floor($dec);
        $seconds -= $degrees * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        return $degrees.'° '.$minutes."' ".$seconds."'' ".$this->calcCompass($degrees);
    }

    function dec2time($dec)
    {
        $seconds = round($dec * 3600);
        $hours = floor($dec);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        return $hours.'h '.$minutes."' ".$seconds."''";
    }

    function calcCompass($deg)
    {
        $d = (round($deg/22.5))%16;
        return $this->session->compass[$d];
    }
}
?>