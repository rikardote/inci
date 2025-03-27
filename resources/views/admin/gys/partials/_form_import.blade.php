<div class="row">
    <div class="col-md-6">
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="col-auto">
                    <label for="csv_file" class="sr-only">Archivo CSV</label>
                    <input type="file" class="form-control" name="csv_file" id="imported" accept=".txt">
                </div>
                <div class="col-auto">
                    <p><button class="btn btn-success btn-sm" type="submit" id="submitBtn" disabled>
                        <i class="fas fa-file-csv"></i>
                    </button></p>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('imported').addEventListener('change', function() {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = !this.files.length;
});
</script>
