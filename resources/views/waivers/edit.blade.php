<!DOCTYPE html>
<html>
<head>
    <title>Edit Waiver</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>

<h1>Edit Waiver</h1>

<form action="{{ route('waivers.update', $waiver) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Waiver Title</label><br>
        <input type="text" 
               name="title" 
               value="{{ old('title', $waiver->title) }}"
               style="width:100%; padding:8px"
               required>
    </div>

    <br>

    <div>
        <label>Waiver Content</label><br>
        <div id="editor" style="height:300px">{!! $waiver->content !!}</div>
        <textarea name="content" id="content" style="display:none"></textarea>
    </div>

    <br>

    <div>
        <label>
            <input type="checkbox" name="require_signature" value="1"
                {{ $waiver->require_signature ? 'checked' : '' }}>
            Require Client Signature
        </label>
    </div>

    <br>

    <button type="submit">✅ Update Waiver</button>
    <a href="{{ route('waivers.index') }}">Cancel</a>
</form>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    var quill = new Quill('#editor', { theme: 'snow' });

    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('content').value = quill.root.innerHTML;
    });
</script>

</body>
</html>