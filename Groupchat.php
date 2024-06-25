<?php
session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $_SESSION['name'] = $name;
}

if (isset($_POST['action']) && $_POST['action'] == 'send_message') {
    $name = $_SESSION['name'];
    $msg = $_POST['msg'];
    // Insert message into the database
    insertMessage($name, $msg);
    exit;
}

function insertMessage($name, $msg) {
    $dsn = 'mysql:host=localhost;dbname=msg';
    $username = 'root';
    $password = '';
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $username, $password, $options);
        $stmt = $pdo->prepare("INSERT INTO chat_messages (username, message) VALUES (:name, :message)");
        $stmt->execute([':name' => $name, ':message' => $msg]);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}

if (!isset($_POST['submit'])) { 
?>

    <form action="index.php" method="POST">
        <input type="text" name="name" required>
        <input type="submit" name="submit" value="Enter">
    </form>

<?php } else { ?>

    <form action="index.php" method="POST">
        <input type="text" id="msg" name="msg">
        <input type="button" name="message" id="btn" value="Send">
    </form>

    <div id="messages"></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        var conn = new WebSocket('ws://localhost:8081');

        conn.onopen = function(e) {
            console.log("Connection established!");
        };

        conn.onmessage = function(e) {
            var data = $.parseJSON(e.data);
            var name = data.name;
            var msg = data.msg;
            var html = "<b>" + name + "</b>: " + msg + "<br/>";
            $('#messages').append(html);
        };

        $("#btn").click(function() {
            var msg = $('#msg').val();
            var name = "<?php echo $_SESSION['name']; ?>";
            var content = {
                name: name,
                msg: msg
            };
            var html = "<b>" + name + "</b>: " + msg + "<br/>";
            $('#messages').append(html);
            conn.send(JSON.stringify(content));
            $('#msg').val('');

            // Send message to server to store in database
            $.post('index.php', {
                action: 'send_message',
                msg: msg
            });
        });
    </script>

<?php } ?>
