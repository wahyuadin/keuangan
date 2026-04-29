<table>
    <thead>
        <tr>
            <th colspan="{{ 6 + (count($listBulan) * 3) + 3 }}" style="font-size: 16px; font-weight: bold; text-align: center;">
                KONSOLIDASI LAPORAN CABANG: {{ strtoupper($branch->nama_branch) }} TAHUN {{ $tahun }}
            </th>
        </tr>
        <tr>
            <th rowspan="2" style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000; text-align: center;">No</th>
            <th rowspan="2" style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000; text-align: center;">Branch Office</th>
            <th rowspan="2" style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000; text-align: center;">Tahun</th>
            <th rowspan="2" style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000; text-align: center;">Kategori</th>
            <th rowspan="2" style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000; text-align: center;">Item Anggaran</th>
            <th rowspan="2" style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000; text-align: center;">Total RKAP</th>

            @foreach ($listBulan as $bln)
                <th colspan="3" style="background-color: #e2e8f0; font-weight: bold; border: 1px solid #000; text-align: center;">{{ ucfirst($bln) }}</th>
            @endforeach

            <th colspan="3" style="background-color: #cbd5e1; font-weight: bold; border: 1px solid #000; text-align: center;">Total Akumulasi</th>
        </tr>
        <tr>
            @foreach ($listBulan as $bln)
                <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000; text-align: center;">Anggaran</th>
                <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000; text-align: center;">Realisasi</th>
                <th style="background-color: #ffffff; font-weight: bold; border: 1px solid #000; text-align: center;">Selisih</th>
            @endforeach
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000; text-align: center;">Saldo Angg</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000; text-align: center;">Realisasi</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #000; text-align: center;">Selisih</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $grand_total_saldo = 0;
            $grand_total_realisasi = 0;
            $grand_total_selisih = 0;
        @endphp

        {{-- Loop per Kategori --}}
        @foreach($groupedData as $kategoriName => $itemsGroup)

            {{-- Loop per Item di dalam kategori tersebut --}}
            @foreach($itemsGroup as $itemId => $reports)
                @php
                    $refItem = $reports->first()->item;
                    $itemName = $refItem->item ?? '-';

                    // Hitung Total RKAP untuk item ini dari semua klinik
                    $totalRKAP = $reports->sum(function($r) {
                        return $r->sla->rkap ?? 0;
                    });

                    $row_total_anggaran = 0;
                    $row_total_realisasi = 0;
                @endphp

                <tr>
                    <td style="border: 1px solid #000; text-align: center;">{{ $no++ }}</td>
                    <td style="border: 1px solid #000;">{{ strtoupper($branch->nama_branch) }}</td>
                    <td style="border: 1px solid #000; text-align: center;">{{ $tahun }}</td>
                    <td style="border: 1px solid #000;">{{ strtoupper($kategoriName) }}</td>
                    <td style="border: 1px solid #000;">{{ strtoupper($itemName) }}</td>
                    <td style="border: 1px solid #000; text-align: right;">{{ $totalRKAP }}</td>

                    @foreach($listBulan as $month)
                        @php
                            // Filter hanya data yang sudah di-verif untuk dihitung
                            $verifiedReports = $reports->filter(function($r) use ($month) {
                                return !empty($r->{$month.'_verif_by'});
                            });

                            $sumAnggaran = $verifiedReports->sum(function($r) use ($month) {
                                $val = $r->$month ?? '0';
                                return (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $val);
                            });

                            $sumRealisasi = $verifiedReports->sum(function($r) use ($month) {
                                $col = $month . '_realisasi';
                                $val = $r->$col ?? '0';
                                return (float) str_replace(['Rp', '.', ' ', ','], ['', '', '', '.'], $val);
                            });

                            $selisih = $sumAnggaran - $sumRealisasi;

                            $row_total_anggaran += $sumAnggaran;
                            $row_total_realisasi += $sumRealisasi;
                        @endphp

                        <td style="border: 1px solid #000; text-align: right;">{{ $sumAnggaran }}</td>
                        <td style="border: 1px solid #000; text-align: right;">{{ $sumRealisasi }}</td>
                        <td style="border: 1px solid #000; text-align: right; color: {{ $selisih < 0 ? 'red' : 'black' }}">{{ $selisih }}</td>
                    @endforeach

                    @php
                        $row_selisih_total = $row_total_anggaran - $row_total_realisasi;
                        $grand_total_saldo += $row_total_anggaran;
                        $grand_total_realisasi += $row_total_realisasi;
                        $grand_total_selisih += $row_selisih_total;
                    @endphp

                    <td style="border: 1px solid #000; text-align: right; background-color: #f8fafc; font-weight: bold;">{{ $row_total_anggaran }}</td>
                    <td style="border: 1px solid #000; text-align: right; background-color: #f8fafc; font-weight: bold;">{{ $row_total_realisasi }}</td>
                    <td style="border: 1px solid #000; text-align: right; background-color: #f8fafc; font-weight: bold; color: {{ $row_selisih_total < 0 ? 'red' : 'black' }}">{{ $row_selisih_total }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="border: 1px solid #000; text-align: right; font-weight: bold; background-color: #e2e8f0;">TOTAL KONSOLIDASI BRANCH</td>

            @foreach ($listBulan as $month)
                <td colspan="3" style="border: 1px solid #000; background-color: #e2e8f0;"></td>
            @endforeach

            <td style="border: 1px solid #000; text-align: right; font-weight: bold; background-color: #cbd5e1;">{{ $grand_total_saldo }}</td>
            <td style="border: 1px solid #000; text-align: right; font-weight: bold; background-color: #cbd5e1;">{{ $grand_total_realisasi }}</td>
            <td style="border: 1px solid #000; text-align: right; font-weight: bold; background-color: #cbd5e1; color: {{ $grand_total_selisih < 0 ? 'red' : 'black' }}">{{ $grand_total_selisih }}</td>
        </tr>
    </tfoot>
</table>
