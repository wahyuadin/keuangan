@php
$jumlahBulan = count($listBulan);
$totalKolom = 2 + $jumlahBulan + 3;
// URAIAN + RKAP + BULAN + JUMLAH + SELISIH + PENCAPAIAN
@endphp

<table>
    <thead>
        {{-- JUDUL PERIODE --}}
        <tr>
            <th colspan="{{ $totalKolom }}" style="text-align:center; font-weight:bold; font-size:12pt;">
                {{ strtoupper($listBulan[0]) }} - {{ strtoupper(end($listBulan)) }} {{ $tahun }}
            </th>
        </tr>

        {{-- NAMA KLINIK --}}
        <tr>
            <th colspan="{{ $totalKolom }}" style="text-align:center; font-weight:bold; font-size:11pt;">
                {{ strtoupper($clinic->nama_klinik ?? 'DATA TIDAK DITEMUKAN') }}
            </th>
        </tr>

        <tr></tr>

        {{-- HEADER TABEL --}}
        <tr>
            <th rowspan="2" style="border:1px solid #000; background:#ffff00; font-weight:bold; text-align:center; vertical-align:middle; width:400px;">
                URAIAN
            </th>

            <th rowspan="2" style="border:1px solid #000; background:#ffff00; font-weight:bold; text-align:center; vertical-align:middle;">
                PENETAPAN RKAP
            </th>

            <th colspan="{{ $jumlahBulan }}" style="border:1px solid #000; background:#6aa84f; color:#fff; font-weight:bold; text-align:center;">
                BULAN
            </th>

            <th rowspan="2" style="border:1px solid #000; background:#ffff00; font-weight:bold; text-align:center;">
                JUMLAH
            </th>

            <th rowspan="2" style="border:1px solid #000; background:#ffff00; font-weight:bold; text-align:center;">
                SELISIH
            </th>

            <th rowspan="2" style="border:1px solid #000; background:#ffff00; font-weight:bold; text-align:center;">
                PENCAPAIAN
            </th>
        </tr>

        {{-- NAMA BULAN --}}
        <tr>
            @foreach($listBulan as $bulan)
            <th style="border:1px solid #000; background:#ffff00; font-weight:bold; text-align:center;">
                {{ strtoupper($bulan) }}
            </th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @if(isset($data) && count($data) > 0)

        @foreach($data as $kategori => $items)

        @php
        $subTotalRKAP = 0;
        $subTotalBulan = array_fill_keys($listBulan, 0);
        $subTotalTahunan = 0;
        @endphp

        {{-- HEADER KATEGORI --}}
        <tr>
            <td style="border:1px solid #000; font-weight:bold; background:#f2f2f2;">
                {{ strtoupper($kategori) }}
            </td>
            <td style="border:1px solid #000; background:#f2f2f2;"></td>

            @foreach($listBulan as $bulan)
            <td style="border:1px solid #000; background:#f2f2f2;"></td>
            @endforeach

            <td style="border:1px solid #000; background:#f2f2f2;"></td>
            <td style="border:1px solid #000; background:#f2f2f2;"></td>
            <td style="border:1px solid #000; background:#f2f2f2;"></td>
        </tr>

        {{-- DETAIL ITEM --}}
        @foreach($items as $report)

        @php
        $rkapVal = (float) str_replace(['.', ','], ['', '.'], $report->sla->rkap ?? 0);
        $subTotalRKAP += $rkapVal;

        $totalRealisasiBaris = 0;
        @endphp

        <tr>
            <td style="border:1px solid #000; padding-left:20px;">
                {{ $report->item->item ?? '-' }}
            </td>

            <td style="border:1px solid #000; text-align:right;">
                {{ $rkapVal != 0 ? number_format($rkapVal, 0, ',', '.') : '-' }}
            </td>

            @foreach($listBulan as $bulan)
            @php
            $verifField = $bulan . '_verif_by';
            $realisasiField = $bulan . '_realisasi';

            $valBulan = 0;

            if (!empty($report->$verifField)) {
            $valBulan = (float) str_replace(['.', ','], ['', '.'], $report->$realisasiField ?? 0);
            }

            $subTotalBulan[$bulan] += $valBulan;
            $totalRealisasiBaris += $valBulan;
            @endphp

            <td style="border:1px solid #000; text-align:right;">
                {{ $valBulan != 0 ? number_format($valBulan, 0, ',', '.') : '-' }}
            </td>
            @endforeach

            @php
            $subTotalTahunan += $totalRealisasiBaris;
            $selisihBaris = $totalRealisasiBaris - $rkapVal;
            $pencapaianBaris = ($rkapVal > 0)
            ? ($totalRealisasiBaris / $rkapVal) * 100
            : 0;
            @endphp

            <td style="border:1px solid #000; text-align:right; font-weight:bold;">
                {{ $totalRealisasiBaris != 0 ? number_format($totalRealisasiBaris, 0, ',', '.') : '-' }}
            </td>

            <td style="border:1px solid #000; text-align:right;">
                {{ $selisihBaris != 0 ? number_format($selisihBaris, 0, ',', '.') : '-' }}
            </td>

            <td style="border:1px solid #000; text-align:right;">
                {{ number_format($pencapaianBaris, 2, ',', '.') }}%
            </td>
        </tr>

        @endforeach

        {{-- TOTAL PER KATEGORI --}}
        @php
        $totalSelisihKategori = $subTotalTahunan - $subTotalRKAP;
        $totalPencapaianKategori = ($subTotalRKAP > 0)
        ? ($subTotalTahunan / $subTotalRKAP) * 100
        : 0;
        @endphp

        <tr>
            <td style="border:1px solid #000; font-weight:bold; background:#e8f0fe;">
                JUMLAH {{ strtoupper($kategori) }}
            </td>

            <td style="border:1px solid #000; font-weight:bold; text-align:right; background:#e8f0fe;">
                {{ number_format($subTotalRKAP, 0, ',', '.') }}
            </td>

            @foreach($listBulan as $bulan)
            <td style="border:1px solid #000; font-weight:bold; text-align:right; background:#e8f0fe;">
                {{ number_format($subTotalBulan[$bulan], 0, ',', '.') }}
            </td>
            @endforeach

            <td style="border:1px solid #000; font-weight:bold; text-align:right; background:#e8f0fe;">
                {{ number_format($subTotalTahunan, 0, ',', '.') }}
            </td>

            <td style="border:1px solid #000; font-weight:bold; text-align:right; background:#e8f0fe;">
                {{ number_format($totalSelisihKategori, 0, ',', '.') }}
            </td>

            <td style="border:1px solid #000; font-weight:bold; text-align:right; background:#e8f0fe;">
                {{ number_format($totalPencapaianKategori, 2, ',', '.') }}%
            </td>
        </tr>

        @endforeach

        @else

        <tr>
            <td colspan="{{ $totalKolom }}" style="text-align:center; border:1px solid #000;">
                Tidak ada data ditemukan.
            </td>
        </tr>

        @endif
    </tbody>
</table>
