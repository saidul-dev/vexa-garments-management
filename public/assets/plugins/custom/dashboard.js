"use strict";

/** dashboard start */
function getDashboardData() {
    var url = $('#get-dashboard').val();
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (res) {
            $('#total_order').text(res.total_order);
            $('#running_order_qty').text(res.running_order_qty);
            $('#pending_order').text(res.pending_order);
            $('#weekly_order_value').text(res.weekly_order_value);
            $('#monthly_order_value').text(res.monthly_order_value);
            $('#current_year_value').text(res.current_year_value);

            $('#total_cash').text(res.total_cash);
            $('#total_bank_balance').text(res.total_bank_balance);
            $('#supplier_due').text(res.supplier_due);
            $('#monthly_expense').text(res.monthly_expense);
            $('#debit_transaction').text(res.debit_transaction);
            $('#credit_transaction').text(res.credit_transaction);
        }
    });
}

$('.earning-expense-month').on('change', function () {
    let year = $(this).val();
    getYearlyStatistics(year)
})

// Function to convert month index to month name
function getMonthNameFromIndex(index) {
    var months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    return months[index - 1];
}

function getYearlyStatistics(year = new Date().getFullYear()) {
    var url = $('#yearly-statistics-url').val();
    $.ajax({
        type: "GET",
        url: url += '?year=' + year,
        dataType: "json",
        success: function (res) {
            var earnings = res.earnings;
            var expenses = res.expenses;
            var total_earnings = [];
            var total_expenses = [];

            for (var i = 0; i <= 11; i++) {
                var monthName = getMonthNameFromIndex(i); // Implement this function to get month name

                var earningsData = earnings.find(item => item.month === monthName);
                total_earnings[i] = earningsData ? earningsData.total : 0;

                var expensesData = expenses.find(item => item.month === monthName);
                total_expenses[i] = expensesData ? expensesData.total : 0;
            }
            totalEarningExpenseChart(total_earnings, total_expenses)
        },
    });
}

let statiSticsValu = false;

function totalEarningExpenseChart(total_earnings, total_expenses) {
    if (statiSticsValu) {
        statiSticsValu.destroy();
    }

    var ctx = document.getElementById('monthly-statistics').getContext('2d');
    var gradient = ctx.createLinearGradient(0, 100, 10, 280);
    gradient.addColorStop(0, '#292BE9');
    gradient.addColorStop(1, 'rgba(50, 130, 241, 0)');

    var gradient2 = ctx.createLinearGradient(0, 0, 0, 290);
    gradient2.addColorStop(0, 'rgba(255, 68, 5, 1)');
    gradient2.addColorStop(1, 'rgba(255, 68, 5, 0)');


    statiSticsValu = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                    backgroundColor: gradient,
                    label: "Income",
                    fill: true,
                    borderWidth: 1,
                    borderColor: "#292BE9",
                    data: total_earnings,
                },
                {
                    backgroundColor: gradient2,
                    label: "Expense",
                    fill: true,
                    borderWidth: 1,
                    borderColor: "rgba(255, 68, 5, 1)",
                    data: total_expenses,
                }
            ]
        },

        options: {
            responsive: true,
            tension: 0.3,
            tooltips: {
                displayColors: true,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 30
                    }
                },
                title: {
                    // display: true,
                }
            },
            scales: {
                x: {
                    display: true,
                },
                y: {
                    display: true,
                    beginAtZero: true
                }
            },
        },
    });
};

let yearlyLcValue = false;

$('.yearly-lc').on('change', function () {
    getYearlyLcValueAjax($(this).val());
})

function getYearlyLcValueAjax(year = new Date().getFullYear()) {
    var url = $('#yearly-lc-value-url').val();
    $.ajax({
        type: "GET",
        url: url += '?year=' + year,
        dataType: "json",
        success: function (res) {
            getYearlyLcValue(res);
        },
    });
}

function getYearlyLcValue(res) {

    if (yearlyLcValue) {
        yearlyLcValue.destroy();
    }

    var roundedCornersFor = {
        "start": Array.from({ length: res.all_countries.length }, (_, i) => i)
    };

    var all_countries = res.all_countries.map(function(item) {
        return item == null ? "Others Country" : item;
    });

    Chart.defaults.elements.arc.roundedCornersFor = roundedCornersFor;
    Chart.defaults.datasets.doughnut.cutout = '65%';
    let salesByCountryChart = $("#sales-by-country");
    yearlyLcValue = new Chart(salesByCountryChart, {
        type: 'doughnut',
        data: {
            labels: all_countries,
            datasets: [{
                label: "Total Lc",
                borderWidth: 0,
                data: res.total_lc_values,
                backgroundColor: [
                    "#FE4F4F",
                    "#00BF71",
                    "#3190FF",
                    "#FFC700",
                ],
                borderColor: [
                    "#FE4F4F",
                    "#00BF71",
                    "#3190FF",
                    "#FFC700",
                ],
            }]
        },

        plugins: [{
            afterUpdate: function(chart) {
                if (chart.options.elements.arc.roundedCornersFor !== undefined) {
                    var arcValues = Object.values(chart.options.elements.arc.roundedCornersFor);

                    arcValues.forEach(function(arcs) {
                        arcs = Array.isArray(arcs) ? arcs : [arcs];
                        arcs.forEach(function(i) {
                            var arc = chart.getDatasetMeta(0).data[i];
                            arc.round = {
                                x: (chart.chartArea.left + chart.chartArea.right) / 2,
                                y: (chart.chartArea.top + chart.chartArea.bottom) / 2,
                                radius: (arc.outerRadius + arc.innerRadius) / 2,
                                thickness: (arc.outerRadius - arc.innerRadius) / 2,
                                backgroundColor: arc.options.backgroundColor
                            }
                        });
                    });
                }
            },
            afterDraw: (chart) => {

            if (chart.options.elements.arc.roundedCornersFor !== undefined) {
                var {
                ctx,
                canvas
                } = chart;
                var arc,
                roundedCornersFor = chart.options.elements.arc.roundedCornersFor;
                for (var position in roundedCornersFor) {
                var values = Array.isArray(roundedCornersFor[position]) ? roundedCornersFor[position] : [roundedCornersFor[position]];
                values.forEach(p => {
                    arc = chart.getDatasetMeta(0).data[p];
                    var startAngle = Math.PI / 2 - arc.startAngle;
                    var endAngle = Math.PI / 2 - arc.endAngle;
                    ctx.save();
                    ctx.translate(arc.round.x, arc.round.y);
                    ctx.fillStyle = arc.options.backgroundColor;
                    ctx.beginPath();
                    if (position == "start") {
                        ctx.arc(arc.round.radius * Math.sin(startAngle), arc.round.radius * Math.cos(startAngle), arc.round.thickness, 0, 2 * Math.PI);
                    } else {
                        ctx.arc(arc.round.radius * Math.sin(endAngle), arc.round.radius * Math.cos(endAngle), arc.round.thickness, 0, 2 * Math.PI);
                    }
                    ctx.closePath();
                    ctx.fill();
                    ctx.restore();
                });

                };
            }
            }
        }],

        options: {
            responsive: true,
            tooltips: {
                displayColors: true,
                zIndex: 999999,
            },
            plugins: {
                legend: {
                position: 'right',
                labels: {
                    usePointStyle: true,
                    padding: 10
                }
                },
                title: {
                    // display: true,
                }
            },
            scales: {
                x: {
                    display: false,
                    stacked: true,
                },
                y: {
                    display: false,
                    stacked: true,
                }
            },
        },
    });
}

let salesRatio = false;

$('.orders-ratio').on('change', function() {
    var year = $(this).val();
    orderRatioChartAjax(year)
});

function orderRatioChartAjax(year = null) {
    var url = $('#orders-ratio').val();
    $.ajax({
        type: "GET",
        url: url += '?year=' + (year ?? ''),
        dataType: "json",
        success: function (res) {

            var response = res.orders;
            var total_qty_by_month = [];

            for (var i = 0; i <= 11; i++) {
                var monthName = getMonthNameFromIndex(i); // Implement this function to get month name

                var orderData = response.find(item => item.month === monthName);
                total_qty_by_month[i] = orderData ? orderData.total_qty : 0;
            }
            orderRatioChart(total_qty_by_month)
        },
    });
}

function orderRatioChart(total_qty_by_month) {
    if (salesRatio) {
        salesRatio.destroy();
    }

    var ctx = document.getElementById('salesRatio').getContext('2d');
    var gradient = ctx.createLinearGradient(0, 100, 10, 210);
    gradient.addColorStop(0, '#292BE9');
    gradient.addColorStop(1, 'rgba(50, 130, 241, 0)');

    var qty_count = total_qty_by_month.reduce(function (accumulator, currentValue) {
        return accumulator + currentValue;
    }, 0);

    salesRatio = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                backgroundColor: gradient,
                label: "Total Orders Qty: " + qty_count,
                fill: true,
                borderWidth: 1,
                borderColor: "#292BE9",
                data: total_qty_by_month,
            }]
        },

        options: {
            responsive: true,
            tension: 0.3,
            tooltips: {
                displayColors: true,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 30
                    }
                },
                title: {
                    // display: true,
                }
            },
            scales: {
                x: {
                    display: true,
                },
                y: {
                    display: true,
                    beginAtZero: true
                }
            },
        },
    });
}

/** dashboard end */
