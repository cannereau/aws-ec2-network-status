<?php

$token = GetMetadata("", "");
$mac = GetMetadata("mac", $token);

function GetMetadata($meta_name, $meta_token) {

    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    if ($meta_token == "") {
            curl_setopt($c, CURLOPT_URL, "http://169.254.169.254/latest/api/token");
            curl_setopt($c, CURLOPT_HTTPHEADER, array("X-aws-ec2-metadata-token-ttl-seconds: 30"));
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, "PUT");
    } else {
            curl_setopt($c, CURLOPT_URL, "http://169.254.169.254/latest/meta-data/$meta_name");
            curl_setopt($c, CURLOPT_HTTPHEADER, array("X-aws-ec2-metadata-token: $meta_token"));
    }
    $response = curl_exec($c);
    $httpcode = intval(curl_getinfo($c, CURLINFO_HTTP_CODE));
    curl_close($c);
    if ($httpcode == 200) {
            return $response;
    } else {
            return "";
    }

}

?><!DOCTYPE html>
<html>
<head>
    <title><?=$_SERVER["HTTP_HOST"]?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style rel="stylesheet" type="text/css">
        html, body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #3d3d3d;
            color: #d8d8d8;
            margin: 0px;
            padding: 0px;
        }
        table {
            font-size: 12px;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
        }
        .cell-left {
            width: 120px;
            text-align: right;
            padding: 5px;
            font-weight: bold;
        }
        .cell-middle {
            width: 160px;
            text-align: left;
            padding: 5px;
        }
        .cell-right {
            width: 120px;
            text-align: left;
            padding: 5px;
            color:  #aaaaaa;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td class="cell-left">Region :</td>
            <td class="cell-middle"><?=GetMetadata("placement/region", $token)?></td>
        </tr><tr>
            <td class="cell-left">Availability Zone :</td>
            <td class="cell-middle"><?=GetMetadata("placement/availability-zone", $token)?></td>
            <td class="cell-right"><?=GetMetadata("placement/availability-zone-id", $token)?></td>
        </tr><tr>
            <td class="cell-left">VPC :</td>
            <td class="cell-middle"><?=GetMetadata("network/interfaces/macs/" . $mac . "/vpc-id", $token)?></td>
            <td class="cell-right"><?=GetMetadata("network/interfaces/macs/" . $mac . "/vpc-ipv4-cidr-block", $token)?></td>
        </tr><tr>
            <td class="cell-left">Subnet :</td>
            <td class="cell-middle"><?=GetMetadata("network/interfaces/macs/" . $mac . "/subnet-id", $token)?></td>
            <td class="cell-right"><?=GetMetadata("network/interfaces/macs/" . $mac . "/subnet-ipv4-cidr-block", $token)?></td>
        </tr><tr>
            <td class="cell-left">Instance ID :</td>
            <td class="cell-middle"><?=GetMetadata("instance-id", $token)?></td>
        </tr><tr>
            <td class="cell-left">Private IP :</td>
            <td class="cell-middle"><?=GetMetadata("local-ipv4", $token)?></td>
        </tr><tr>
            <td class="cell-left">Public IP :</td>
            <td class="cell-middle"><?=GetMetadata("public-ipv4", $token)?></td>
        </tr><tr>
            <td class="cell-left">Client IP :</td>
            <td class="cell-middle"><?=$_SERVER["REMOTE_ADDR"]?></td>
        </tr><tr>
            <td class="cell-left">HTTP Host :</td>
            <td class="cell-middle" colspan="2"><?=$_SERVER["HTTP_HOST"]?></td>
        </tr>
    </table>
</body>
</html>
