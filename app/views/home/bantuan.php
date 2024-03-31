<tr nobr="true">
    <td style="line-height: 20em;width:' . (0.5 / 10 * $lebar_net) . 'mm;">' . $jumlah . '.</td>
    <td style="line-height: 20em;width:' . (2 / 10 * $lebar_net) . 'mm;">Nama<br>Pangkat/Gol<br>NIP<br>Jabatan<br>Keterangan</td>
    <td style="line-height: 20em;width:' . (0.5 / 10 * $lebar_net) . 'mm;">:<br>:<br>:<br>:<br>:</td>
    <td style="line-height: 20em;width:' . (7 / 10 * $lebar_net) . 'mm; text-align: justify;"><span style="font-weight:bold;">' . $value["nama"] . '</span><br>' . $value["pangkat"] . '<br>' . $value["nip"] . '<br>' . ucwords($value["jabatan"]) . '<br>' . ucwords($value["jabatan_sk"]) . '</td>
</tr>