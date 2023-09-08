<body>
    <!-- Đối tượng canvas để vẽ biểu đồ -->
    <canvas id="myChart" ></canvas>
    <script>
        var data = {
            labels: ["Scope 1", "Scope 2", "Scope 3"]
            , datasets: [{
                    label: "010. Gaseous Fuel"
                    , data: [2.39, 0]
                    , backgroundColor: "red"
                }
                , {
                    label: "020. Refrigerants"
                    , data: [0.69, 0]
                    , backgroundColor: "blue"
                }
                , {
                    label: "030. Own Passenger Vehicles"
                    , data: [7.57, 0]
                    , backgroundColor: "green"
                }
                , {
                    label: "040. Delivery & Controlled Vehicles"
                    , data: [17.42, 0]
                    , backgroundColor: "orange"
                }
                , {
                    label: "050. Electricity"
                    , data: [0, 216.91]
                    , backgroundColor: "purple"
                }
                , {
                    label: "060. Water Supply & Treatment"
                    , data: [0,0,100]
                    , backgroundColor: "green"
                }
                , {
                    label: "070. Materials"
                    , data: [0,0,1017.47]
                    , backgroundColor: "yellow"
                }
                , {
                    label: "080. Waste Disposal"
                    , data: [0, 0,70]
                    , backgroundColor: "red"
                }
            ]
        };
        // Lấy thẻ canvas và vẽ biểu đồ ngang
        var ctx = document.getElementById("myChart").getContext("2d");
        var myChart = new Chart(ctx, {
            type: "bar"
            , data: data
            , options: {
                scales: {
                    x: {
                        stacked: true
                    }
                    , y: {
                        stacked: true
                    }
                }
                , indexAxis: 'y'
            }
        });

    </script>
</body>
