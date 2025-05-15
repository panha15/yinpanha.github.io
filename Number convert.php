<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number to Khmer Converter</title>
    <style>
        body {
            font-family: 'Khmer OS Battambang', 'Arial', sans-serif;
            background-color: #f4f8fc;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background: linear-gradient(to right, #ffffff, #e8f0fe);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #003366;
            margin-bottom: 25px;
            font-size: 24px;
        }

        form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        input[type="text"], select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            flex: 1;
            background-color: #ffffff;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        strong {
            color: #222;
        }

        .khmer {
            color: #880000;
            font-weight: bold;
        }

        .usd {
            color: green;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🔢 Convert Number to Khmer and English</h1>
    <form method="POST">
        <select name="mode" id="mode">
            <option value="num">Number</option>
        </select>
        <input type="text" name="number" placeholder="Enter a number..." autocomplete="off">
        <button type="submit">Convert</button>
    </form>

    <?php
    function numberToKhmerWords($number) {
        $khmerNumbers = ['', 'មួយ', 'ពីរ', 'បី', 'បួន', 'ប្រាំ', 'ប្រាំមួយ', 'ប្រាំពីរ', 'ប្រាំបី', 'ប្រាំបួន'];
        $khmerTens = [10 => 'ដប់', 20 => 'ម្ភៃ', 30 => 'សាមសិប', 40 => 'សែសិប', 50 => 'ហាសិប', 60 => 'ហុកសិប', 70 => 'ចិតសិប', 80 => 'ប៉ែតសិប', 90 => 'កៅសិប'];
        $unitPositions = [1000000 => 'លាន', 100000 => 'សែន', 10000 => 'ម៉ឺន', 1000 => 'ពាន់', 100 => 'រយ'];

        if ($number == 0) return 'សូន្យ';

        $result = '';
        foreach ($unitPositions as $value => $word) {
            if ($number >= $value) {
                $unitValue = floor($number / $value);
                $number %= $value;
                $result .= numberToKhmerWords($unitValue) . $word;
            }
        }

        if ($number >= 10) {
            if (isset($khmerTens[$number])) {
                $result .= $khmerTens[$number] . ' ';
            } else {
                $tens = floor($number / 10) * 10;
                $ones = $number % 10;
                $result .= $khmerTens[$tens] . ' ' . $khmerNumbers[$ones] . ' ';
            }
        } elseif ($number > 0) {
            $result .= $khmerNumbers[$number] . ' ';
        }

        return trim($result);
    }

    function numberToEnglishWords($number) {
        $ENNumbers = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        $ENTeens = [11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen'];
        $ENTens = [10 => 'Ten', 20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'];
        $ENUnits = [1000000 => 'Million', 1000 => 'Thousand', 100 => 'Hundred'];

        if ($number == 0) return 'Zero';

        $result = '';
        foreach ($ENUnits as $value => $word) {
            if ($number >= $value) {
                $unitValue = floor($number / $value);
                $number %= $value;
                $result .= numberToEnglishWords($unitValue) . ' ' . $word . ' ';
            }
        }

        if ($number >= 11 && $number <= 19) {
            $result .= $ENTeens[$number] . ' ';
        } elseif ($number >= 10) {
            $tens = floor($number / 10) * 10;
            $ones = $number % 10;
            $result .= $ENTens[$tens] . ' ' . $ENNumbers[$ones] . ' ';
        } elseif ($number > 0) {
            $result .= $ENNumbers[$number] . ' ';
        }

        return trim($result);
    }

    function ConvertKHRtoUSD($number) {
        $NewNumber = $number / 4000;
        return number_format($NewNumber, 2, '.', '');
    }

    function InputFileKHR($GetNumber, $number) {
        $filename = "Exchange.txt";
        $file = fopen($filename, "a");
        $finalResult = $GetNumber . " ដុល្លារ\t\t" . $number . " រៀល\n";
        if ($file) {
            fwrite($file, $finalResult);
            fclose($file);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $number = $_POST["number"];
        if (is_numeric($number)) {
            $convertedTextKH = numberToKhmerWords((int)$number);
            $convertedTextEN = numberToEnglishWords((int)$number);
            $usd = ConvertKHRtoUSD($number);
            InputFileKHR($usd, $number);

            echo "<p>🇬🇧 English : <strong>$number</strong> = <strong>$convertedTextEN Riel</strong></p>";
            echo "<p class='khmer'>🇰🇭 Khmer : <strong>$number</strong> = <strong>$convertedTextKH រៀល</strong></p>";
            echo "<p class='usd'>💵 បំលែងជាលុយដុល្លារ : <strong>$usd $</strong></p>";
        } else {
            echo "<p class='error'>❌ Please enter a valid number.</p>";
        }
    }
    ?>
</div>
</body>
</html>
