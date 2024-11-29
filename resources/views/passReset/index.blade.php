<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <form action="{{ route('submit') }}" method="POST">
        @csrf
        <input type="text" name="code">
        <button type="submit">send</button>
    </form>

    <script>
        // PHP থেকে প্রাপ্ত ব্লক শেষ হওয়ার সময় (Unix timestamp)
        var blockEndTime = {{ $expiryDate }}  * 1000; // JavaScript এর জন্য মিলিসেকেন্ডে
        var timer; // টাইমার ভেরিয়েবল

        // ফাংশন: সময় গণনা এবং আপডেট
        function countdownTimer() {
            var now = new Date().getTime();
            var timeLeft = blockEndTime - now;

            // সময়ের বিভাজন (দিন, ঘন্টা, মিনিট, সেকেন্ড)
            var days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            // HTML এ ফলাফল দেখানো (h1 ট্যাগের মধ্যে)
            document.getElementById("countdown").innerHTML =
                days + " দিন " + hours + " ঘন্টা " + minutes + " মিনিট " + seconds + " সেকেন্ড ";

            // কাউন্টডাউন শেষ হলে কি হবে
            if (timeLeft < 0) {
                clearInterval(timer);
                document.getElementById("countdown").innerHTML = "ব্লক সময় শেষ হয়েছে!";
            }
        }

        // পেজ লোড হলে টাইমার শুরু হবে
        window.onload = function() {
            timer = setInterval(countdownTimer, 1000);
        };
    </script>

    <h1 id="countdown">কাউন্টডাউন লোড হচ্ছে...</h1>

</body>

</html>
