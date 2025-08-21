<x-filament-panels::page>
    <h2 class="text-lg font-bold mb-4 text-center">Scan QR Code</h2>

    <div id="reader" style="width: 300px; margin: auto;"></div>

    <div class="mt-6 max-w-md mx-auto">
        <h3 class="text-md font-semibold mb-2">Hasil Scan:</h3>
        <div id="result" class="text-green-700 text-sm font-semibold"></div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        const resultContainer = document.getElementById("result");
        const qrReader = new Html5Qrcode("reader");

        // Masukkan ID dari Laravel (misalnya dari controller atau Livewire)
        const kilnDryId = @json($record->id); // aman dan otomatis escape

        let alreadyScanned = false;

        qrReader.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            async (decodedText, decodedResult) => {
                if (!alreadyScanned) {
                    alreadyScanned = true;

                    // Gabungkan hasil QR dengan id KilnDry
                    resultContainer.textContent = `QR: ${decodedText} | ID KilnDry: ${kilnDryId}`;

                    await qrReader.stop();


                   await fetch('/kilndry', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
        no_register: decodedText,
        kiln_dries_id: kilnDryId
    })
})
.then(response => {
    if (response.ok) {
        // Redirect ke halaman detail KilnDry (atau sesuaikan dengan route yang kamu pakai)
        window.location.href = `/admin/kiln-dries/details-kiln-dry/${kilnDryId}`;
    } else {
        resultContainer.textContent = 'Gagal mengirim data ke server.';
        alreadyScanned = false;
    }
})
.catch(error => {
    resultContainer.textContent = 'Terjadi kesalahan.';
    alreadyScanned = false;
});



                }
            },
            error => {
                // silent error
            }
        );
    </script>
</x-filament-panels::page>
