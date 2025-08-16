<!DOCTYPE html>
<html>
<head>
    <title>Absolut Minimal</title>
</head>
<body>
    <h1>Absolut minimaler Test - KEIN Framework, KEIN jQuery</h1>
    
    <form id="test" action="examples/process.php" method="POST">
        <input type="hidden" name="form_id" value="minimal">
        <input type="text" name="test" required placeholder="Eingabe erforderlich">
        <button type="submit">ABSENDEN</button>
    </form>
    
    <hr>
    
    <h3>Mit JavaScript preventDefault:</h3>
    <form id="test2">
        <input type="text" name="test2" required placeholder="Eingabe erforderlich">
        <button type="submit">ABSENDEN MIT JS</button>
    </form>
    
    <div id="output"></div>
    
    <script>
    // Einfacher JavaScript Handler
    document.getElementById('test2').addEventListener('submit', function(e) {
        e.preventDefault();
        document.getElementById('output').innerHTML = '<h2 style="color: green;">FORM 2 SUBMITTED!</h2>';
        alert('Form 2 submitted!');
        return false;
    });
    
    console.log('Handlers attached');
    </script>
</body>
</html>