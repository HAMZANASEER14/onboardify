<!DOCTYPE html>
<html>
<head>
    <title>Edit Waiver</title>
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