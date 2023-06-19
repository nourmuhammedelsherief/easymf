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
                    <td class="text-center"> {{ $branch->subscription->id }} </td>
                    <td style="text-align: right"> رقم الفاتورة </td>
                </tr>
                @if($branch->subscription->status == 'active' or $branch->subscription->status == 'finished')
                    <tr>
                        <td>Invoice Date</td>
                        <td class="text-center"> {{ $branch->subscription->end_at->addYears(-1)->format('Y-m-d') }} </td>
                        <td style="text-align: right"> تاريخ إصدار الفاتورة </td>
                    </tr>
                @else
                    <tr>
                        <td>Invoice Date</td>
                        <td class="text-center"> {{ $branch->subscription->end_at->addWeek(-1)->format('Y-m-d') }} </td>
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
            <span>Customer Info - بيانات العميل </span>
        </h3>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td>Name</td>
                <td class="text-center">
                    {{ app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en}}
                </td>
                <td style="text-align: right"> الاسم </td>
            </tr>
            <tr>
                <td> Mobile Number </td>
                <td class="text-center">
                    {{$branch->restaurant->phone_number}}
                </td>
                <td style="text-align: right"> رقم الجوال </td>
            </tr>
            <tr>
                <td> Address </td>
                <td class="text-center">
                    {{ app()->getLocale() == 'ar' ? $branch->city->name_ar : $branch->city->name_en}}
                </td>
                <td style="text-align: right"> العنوان </td>
            </tr>
            @if($branch->tax_number != null)
                <tr>
                    <td> Tax Number </td>
                    <td class="text-center">
                        {{ $branch->tax_number  }}
                    </td>
                    <td style="text-align: right"> الرقم  الضريبي </td>
                </tr>
            @endif
            <tr>
                <td>Service</td>
                <td class="text-center">
                    @if($branch->subscription->type == 'restaurant')
                        @if($branch->subscription->created_at < \Carbon\Carbon::now()->addYears(-1))
                            تجديد حساب
                        @else
                            اشتراك حساب جديد
                        @endif
                    @else
                        @if($branch->subscription->created_at < \Carbon\Carbon::now()->addYears(-1))
                            تجديد حساب فرع
                        @else
                            اشتراك فرع جديد
                        @endif
                    @endif
                </td>
                <td style="text-align: right"> الخدمة </td>
            </tr>
            <tr>
                <td>Paid Status</td>
                <td class="text-center">
                    @if($branch->subscription->status == 'tentative' or $branch->subscription->status == 'tentative_finished')
                        <span class="btn btn-danger">غير مدفوع - Not Paid</span>
                    @elseif($branch->subscription->end_at < \Carbon\Carbon::now()->addMonth())
                        <span class="btn btn-danger"> تجديد (غير مدفوع)- Not Paid</span>
                    @elseif($branch->subscription->status == 'active')
                        <span class="btn btn-success">مدفوع - Paid</span>
                    @else
                        <span> غير مشترك - Not Subscribed</span>
                    @endif
                </td>
                <td style="text-align: right"> حاله الدفع </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <h3 class="text-center alert alert-primary">
            <span> Items - العناصر </span>
        </h3>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td> Price </td>
                <td class="text-center">
                    @if($branch->main == 'true')
                        {{number_format((float)$branch->subscription->package->price, 2, '.', '')}}
                    @else
                        {{number_format((float)$branch->subscription->package->branch_price, 2, '.', '')}}
                    @endif
                </td>
                <td style="text-align: right"> السعر </td>
            </tr>
            @if($branch->subscription->seller_code)
                <tr>
                    <td> Seller Code </td>
                    <td class="text-center">
                        @if($branch->subscription->seller_code)
                            {{$branch->subscription->seller_code->seller_name}}
                        @else
                            {{app()->getLocale() == 'ar' ? 'لا يوجد' : 'not found'}}
                        @endif
                    </td>
                    <td style="text-align: right"> كود الخصم </td>
                </tr>
            @endif
            @if($branch->subscription->discount_value > 0)
                <tr>
                    <td> Discount </td>
                    <td class="text-center">
                        {{$branch->subscription->discount_value}}
                    </td>
                    <td style="text-align: right"> الخصم </td>
                </tr>
            @endif
            @if($branch->subscription->tax_value > 0)
                <tr>
                    <td> Tax </td>
                    <td class="text-center">
                        {{number_format((float)$branch->subscription->tax_value, 2, '.', '')}}
                    </td>
                    <td style="text-align: right"> ضريبة القيمة المضافة </td>
                </tr>
            @endif
            <tr>
                <td> Total </td>
                <td class="text-center">
                    {{number_format((float)$branch->subscription->price, 2, '.', '')}}
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
                anchorTag.download = "{{$branch->name_ar}}-invoice.jpg";
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
