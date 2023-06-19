<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
            integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
            crossorigin="anonymous"></script>
</head>
<body>
<h3 class="text-center">
    <a href="#" id="printPage" class="printPage btn btn-info">
        {{app()->getLocale() == 'ar' ? 'تحميل الفاتورة': 'Download Invoice'}}
    </a>
</h3>
<div class="container" id="barcode-svg">
    <div class="row">
        <h3 class="text-center alert alert-primary">
            <span> Invoice - الفاتورة </span>
        </h3>
        <div class="col-sm-3">
            {!! QrCode::size(150)->generate('https://web.easymenu.site/') !!}
            <div class="description" style="margin-top:10px;">
                <img width="40px" height="40px" src="{{asset('uploads/img/logo.png')}}">
            </div>
        </div>
        <div class="col-sm-9">
            <br>
            <br>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td>Invoice Number</td>
                    <td class="text-center"> {{ $service->id }} </td>
                    <td style="text-align: right"> رقم الفاتورة </td>
                </tr>
                @if($service->paid_at != null)
                    <tr>
                        <td>Invoice Date</td>
                        <td class="text-center"> {{ $service->paid_at->format('Y-m-d') }} </td>
                        <td style="text-align: right"> تاريخ إصدار الفاتورة </td>
                    </tr>
                @endif
                <tr>
                    <td> The Company </td>
                    <td class="text-center"> تقني للبرمجيات وتقنيه المعلومات </td>
                    <td style="text-align: right"> الشركة </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <h3 class="text-center alert alert-primary">
            <span> Customer Info - بيانات العميل</span>
        </h3>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td>Name</td>
                <td class="text-center">
                    {{ app()->getLocale() == 'ar' ? $service->restaurant->name_ar: $service->restaurant->name_en }}
                </td>
                <td style="text-align: right"> الاسم </td>
            </tr>
            <tr>
                <td> Mobile Number </td>
                <td class="text-center">
                    {{$service->restaurant->phone_number}}
                </td>
                <td style="text-align: right"> رقم الجوال </td>
            </tr>
            <tr>
                <td> Address </td>
                <td class="text-center">
                    {{ app()->getLocale() == 'ar' ? $service->restaurant->city->name_ar : $service->restaurant->city->name_en}}
                </td>
                <td style="text-align: right"> العنوان </td>
            </tr>
            <tr>
                <td> Branch </td>
                <td class="text-center">
                    {{isset($service->branch->id)  ? ( app()->getLocale() == 'ar' ? $service->branch->name_ar : $service->branch->name_en) : null}}
                </td>
                <td style="text-align: right"> الفرع </td>
            </tr>
            <tr>
                <td>Service</td>
                <td class="text-center">
                    {{ $service->service->name }}
                </td>
                <td style="text-align: right"> الخدمة </td>
            </tr>
            <tr>
                <td>Payment Status</td>
                <td class="text-center">
                    @if($service->status == 'active')
                        <a class="btn btn-success" href="#"> مدفوع</a>
                    @else
                        <a class="btn btn-danger" href="#"> غير مدفوع</a>
                    @endif
                </td>
                <td style="text-align: right"> حالة الدفع </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <h3 class="text-center alert alert-primary">
            <span> Items - العناصر </span>
        </h3>
        @php
        $tentative_price = $service->service->price;
        $tax = \App\Models\Setting::first()->tax;
        $tentative_tax = ($tentative_price * $tax) / 100;
        $total_tentative = $tentative_price + $tentative_tax;
        @endphp
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td> Price </td>
                <td class="text-center">
                    {{number_format((float)$service->service->price, 2, '.', '')}}
                </td>
                <td style="text-align: right"> السعر </td>
            </tr>
            @if($service->discount > 0)
                <tr>
                    <td> Discount </td>
                    <td class="text-center">
                        {{number_format((float)$service->discount, 2, '.', '')}}
                    </td>
                    <td style="text-align: right"> الخصم </td>
                </tr>
            @endif
            @if($service->seller_code_id != null)
                <tr>
                    <td> Seller Code </td>
                    <td class="text-center">
                        {{$service->seller_code->seller_name}}
                    </td>
                    <td style="text-align: right"> كود الخصم </td>
                </tr>
            @endif
            @if($service->tax_value > 0)
                <tr>
                    <td> Tax </td>
                    <td class="text-center">
                        {{number_format((float)$service->tax_value, 2, '.', '')}}
                    </td>
                    <td style="text-align: right"> قيمة الضريبة المضافة </td>
                </tr>
            @else
                <tr>
                    <td> Tax </td>
                    <td class="text-center">
                        {{number_format((float)$tentative_tax, 2, '.', '')}}
                    </td>
                    <td style="text-align: right"> قيمة الضريبة المضافة </td>
                </tr>
            @endif
            <tr>
                <td> Total </td>
                <td class="text-center">
                    @if($service->price > 0)
                        {{number_format((float)$service->price, 2, '.', '')}}
                    @else
                        {{number_format((float)$total_tentative, 2, '.', '')}}
                    @endif
                </td>
                <td style="text-align: right"> الإجمالي </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


<script src="{{asset('dist/js/html2canvas.min.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {

        document.getElementById("printPage").addEventListener("click", function() {
            html2canvas(document.getElementById("barcode-svg")).then(function (canvas) {
                var anchorTag = document.createElement("a");
                document.body.appendChild(anchorTag);
                // document.getElementById("previewImg").appendChild(canvas);
                anchorTag.download = "{{$service->service->name}}-invoice.jpg";
                anchorTag.href = canvas.toDataURL();
                anchorTag.target = '_blank';
                anchorTag.click();
            });
        });

        // $("a.printPage").click(function () {
        //     $("#printarea").print();
        // });
    });
</script>
</body>
</html>
