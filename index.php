<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Фільтр товарів</title>
    <script>
        // Класичний XHR-запит
        function sendRequest(url, format, callback) {
            let xhr = new XMLHttpRequest();
            xhr.open('GET', url + '&format=' + format, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (format === 'xml') {
                        callback(xhr.responseXML);
                    } else if (format === 'json') {
                        callback(JSON.parse(xhr.responseText));
                    } else {
                        callback(xhr.responseText);
                    }
                }
            };
            xhr.send();
        }

        // JSONP-запит
        function sendJsonpRequest(url, callbackName) {
            let script = document.createElement('script'); // Створення скрипта
            script.src = url + '&format=jsonp&callback=' + callbackName; // Додання параметрів
            document.body.appendChild(script); // Додавання до DOM
        }

        // Завантаження за виробником (XHR)
        function loadByVendor(format) {
            let vendorId = document.querySelector('[name="vendor_id"]').value;
            sendRequest('get_by_vendor.php?vendor_id=' + vendorId, format, function (response) {
                renderResponse(response, format);
            });
        }

        // Завантаження за виробником (JSONP)
        function loadByVendorJSONP() {
            let vendorId = document.querySelector('[name="vendor_id"]').value;
            sendJsonpRequest('get_by_vendor.php?vendor_id=' + vendorId, 'handleVendorData');
        }

        // Глобальна функція обробки JSONP-даних
        function handleVendorData(data) {
            renderResponse(data, 'json');
        }

        function loadByCategory(format) {
            let categoryId = document.querySelector('[name="category_id"]').value;
            sendRequest('get_by_category.php?category_id=' + categoryId, format, function (response) {
                renderResponse(response, format);
            });
        }

        function loadByPrice(format) {
            let minPrice = document.querySelector('[name="min_price"]').value;
            let maxPrice = document.querySelector('[name="max_price"]').value;
            sendRequest('get_by_price.php?min_price=' + minPrice + '&max_price=' + maxPrice, format, function (response) {
                renderResponse(response, format);
            });
        }

        // Вивід результатів
        function renderResponse(response, format) {
            let resultDiv = document.getElementById('result');
            resultDiv.innerHTML = '';

            if (format === 'xml') {
                let items = response.getElementsByTagName('item');
                for (let item of items) {
                    resultDiv.innerHTML += `<p>${item.getElementsByTagName('name')[0].textContent} - ${item.getElementsByTagName('price')[0].textContent}$</p>`;
                }
            } else if (format === 'json') {
                response.forEach(item => {
                    resultDiv.innerHTML += `<p>${item.name} - ${item.price}$</p>`;
                });
            } else {
                resultDiv.innerHTML = response;
            }
        }
    </script>
    <style>
        .filter-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 800px;
        }
    </style>
</head>
<body>
    <h1>Фільтр товарів</h1>
    <div class="filter-container">
        <div>
            <label>Оберіть виробника:</label>
            <select name="vendor_id">
                <?php
                include 'connection.php';
                $vendors = $pdo->query("SELECT ID_Vendors, v_name FROM vendors")->fetchAll();
                foreach ($vendors as $vendor) {
                    echo "<option value='{$vendor['ID_Vendors']}'>{$vendor['v_name']}</option>";
                }
                ?>
            </select>
            <button onclick="loadByVendor('text')">HTML</button>
            <button onclick="loadByVendor('xml')">XML</button>
            <button onclick="loadByVendor('json')">JSON</button>
            <button onclick="loadByVendorJSONP()">JSONP</button> <!-- нова кнопка -->
        </div>

        <div>
            <label>Оберіть категорію:</label>
            <select name="category_id">
                <?php
                $categories = $pdo->query("SELECT ID_Category, c_name FROM category")->fetchAll();
                foreach ($categories as $category) {
                    echo "<option value='{$category['ID_Category']}'>{$category['c_name']}</option>";
                }
                ?>
            </select>
            <button onclick="loadByCategory('text')">HTML</button>
            <button onclick="loadByCategory('xml')">XML</button>
            <button onclick="loadByCategory('json')">JSON</button>
        </div>

        <div>
            <label>Мінімальна ціна:</label>
            <input type="number" name="min_price">
            <label>Максимальна ціна:</label>
            <input type="number" name="max_price">
            <button onclick="loadByPrice('text')">HTML</button>
            <button onclick="loadByPrice('xml')">XML</button>
            <button onclick="loadByPrice('json')">JSON</button>
        </div>
    </div>

    <div id="result"></div>
</body>
</html>
