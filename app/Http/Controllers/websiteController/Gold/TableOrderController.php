<?php

namespace App\Http\Controllers\websiteController\Gold;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Day;
use App\Models\FoodicsDiscount;
use App\Models\LoyaltyPointHistory;
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductSize;
use App\Models\RestaurantOrderPeriod;
use App\Models\RestaurantOrderSellerCode;
use App\Models\RestaurantOrderSetting;
use App\Models\ServiceSubscription;
use App\Models\Table;
use App\Models\TableOrder;
use App\Models\TableOrderItem;
use App\Models\TableOrderItemOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TableOrderController extends Controller
{
    public function add_to_table_cart(Request $request)
    {
        $meal = Product::find($request->mealId);
        $table = Table::find($request->table_id);
        $restaurant = $meal->restaurant;
        $this->checkTheme($restaurant);
        $totalCount = 'total' . $meal->id;
        if ($request->size_id == null) {

            if (empty($request->options)) {
                // check if the options is required
                $check_required_options = ProductOption::whereProductId($meal->id)
                    ->where('min', '>=', 1)
                    ->first();
                if ($check_required_options) {
                    flash('الإضافات مطلوبة')->error();
                    return back();
                }
            } else {
                $option_count = 0;
                foreach ($request->options as $options_id) {
                    $option = Option::find($options_id);
                    $modifier = $option->modifier;
                    $option_count++;
                    if ($modifier->choose == 'one' and $option_count > 1) {
                        flash('لا يمكنك طلب أكثر من أضافة')->error();
                        return redirect()->back();
                    }
                }
            }
            $checkOrder = TableOrder::where('restaurant_id', $restaurant->id)
                ->where('status', 'in_reservation')
                ->where('branch_id', $meal->branch->id)
                ->where('ip', Session::getId())
                ->whereTableId($table->id)
                ->first();
            if ($checkOrder) {
                $order = $checkOrder;
            } else {
                $order = TableOrder::create([
                    'restaurant_id' => $restaurant->id,
                    'branch_id' => $meal->branch->id,
                    'table_id' => $table->id,
                    'status' => 'in_reservation',
                    'notes' => $request->notes == null ? null : $request->notes,
                    'ip' => Session::getId(),

                ]);
            }
            // create order
            $item = TableOrderItem::create([
                'table_order_id' => $order->id,
                'product_id' => $meal->id,
                'product_count' => $request->$totalCount,
//                    'size_id'        => $request->size_price_id,
                'price' => $meal->price,
            ]);
            // create options
            $options_price = 0;
            if ($request->options_ids != null && $request->options != null) {
                foreach ($request->options as $options_id) {
                    $opName = 'qty' . $options_id . $meal->id;
                    // create order options
                    TableOrderItemOption::create([
                        'table_order_item_id' => $item->id,
                        'option_id' => $options_id,
                        'option_count' => $request->$opName,
                    ]);
                    $options_price += Option::find($options_id)->price * $request->$totalCount;
                }
            }
            $totalName = 'total' . $meal->id;
            $order_price = ($meal->price * $request->$totalName) + $options_price;
            $tax = 0;
            if ($meal->branch->tax == 'true') {
                $tax = ($meal->branch->tax_value * $order_price) / 100;
            }
            $totalPrice = $order_price + $tax;
            if ($checkOrder == null) {
                $order->update([
                    'order_price' => $order_price,
                    'tax' => $tax,
                    'total_price' => $totalPrice,
                ]);
            } else {
                $order->update([
                    'order_price' => ($order_price + $checkOrder->order_price),
                    'tax' => ($tax + $checkOrder->tax),
                    'total_price' => ($totalPrice + $checkOrder->total_price),
                ]);
            }
        } else {
            if (empty($request->options)) {
                // check if the options is required
                $check_required_options = ProductOption::whereProductId($meal->id)
                    ->where('min', '>=', 1)
                    ->first();
                if ($check_required_options) {
                    flash('الإضافات مطلوبة')->error();
                    return back();
                }
            }
            // create order
            $checkOrder = TableOrder::where('restaurant_id', $restaurant->id)
                ->where('status', 'in_reservation')
                ->where('branch_id', $meal->branch->id)
                ->where('ip', Session::getId())
                ->first();
            if ($checkOrder) {
                $order = $checkOrder;
            } else {
                $order = TableOrder::create([
                    'restaurant_id' => $restaurant->id,
                    'branch_id' => $meal->branch->id,
                    'table_id' => $table->id,
                    'status' => 'in_reservation',
                    'notes' => $request->notes == null ? null : $request->notes,
                    'ip' => Session::getId(),

                ]);
            }
            $size = ProductSize::whereProductId($meal->id)
                ->where('price', $request->size_id)
                ->first();
            $item = TableOrderItem::create([
                'table_order_id' => $order->id,
                'product_id' => $meal->id,
                'product_count' => $request->$totalCount,
                'size_id' => $size->id,
                'price' => $size->price,
            ]);
            // create options
            $options_price = 0;
            if ($request->options_ids != null && $request->options != null) {
                foreach ($request->options as $options_id) {
                    $opName = 'qty' . $options_id . $meal->id;
                    // create order options
                    TableOrderItemOption::create([
                        'table_order_item_id' => $item->id,
                        'option_id' => $options_id,
                        'option_count' => $request->$opName,
                    ]);
                    $options_price += Option::find($options_id)->price * $request->$totalCount;
                }
            }
            $totalName = 'total' . $meal->id;
            $order_price = ($size->price * $request->$totalName) + $options_price;
            $tax = 0;
            if ($meal->branch->tax == 'true') {
                $tax = ($meal->branch->tax_value * $order_price) / 100;
            }
            $totalPrice = $order_price + $tax;
            if ($checkOrder == null) {
                $order->update([
                    'order_price' => $order_price,
                    'tax' => $tax,
                    'total_price' => $totalPrice,
                ]);
            } else {
                $order->update([
                    'order_price' => ($order_price + $checkOrder->order_price),
                    'tax' => ($tax + $checkOrder->tax),
                    'total_price' => ($totalPrice + $checkOrder->total_price),
                ]);
            }
        }
        $branch = $table->branch;
        $cartCount = TableOrderItem::where('table_order_id', $order->id)->count();
        if ($request->wantsJson()):
            if (!isset($cartCount)):
                $cartCount = 0;
            endif;
            return response([
                'status' => true,
                'message' => trans('messages.saved_cart_success'),
                'data' => [
                    'cart_count' => $cartCount,
                ],
                // 'request'=> $request->all()
            ], 200);
        endif;
        return redirect()->to(url()->previous());
    }

    public function tableGetCart($id, $table_id = null)
    {
        $branch = Branch::findOrFail($id);
        $table = Table::findOrFail($table_id);
        // check if service available or not
        if ($table->service_id != null):
            $service_subscription = ServiceSubscription::whereRestaurantId($branch->restaurant_id)
                ->whereBranchId($branch->id)
                ->whereServiceId($table->service_id)
                ->first();
            if ($service_subscription == null or ($service_subscription != null and $service_subscription->status != 'tentative' and $service_subscription->status != 'active')):
                abort(404);
            endif;
        endif;
        $order = TableOrder::whereTableId($table->id)
            ->where('status', 'in_reservation')
            ->where('branch_id', $branch->id)
            ->where('ip', Session::getId())
            ->first();
        if ($order) {
            $items = TableOrderItem::with('table_order')
                ->whereHas('table_order', function ($q) {
                    $q->where('status', 'in_reservation');
                })
                ->where('table_order_id', $order->id)
                ->get();
        } else {
            $items = TableOrderItem::limit(0);
        }
//        dd($items);
        $restaurant = $branch->restaurant;
        $this->checkTheme($restaurant);
        // return $branch;
        return view('website.' . session('theme_path') . 'table.accessories.cart', compact('order', 'table', 'items', 'branch', 'restaurant'));
    }

    public function apply_table_order_seller_code(Request $request, $id)
    {
        $order = TableOrder::findOrFail($id);
        $this->validate($request, [
            'seller_code' => 'required|exists:restaurant_order_seller_codes,seller_code',
        ]);
        $seller_code = RestaurantOrderSellerCode::whereSellerCode($request->seller_code)
            ->where('restaurant_id', $order->restaurant_id)
            ->whereDate('start_at', '<=', Carbon::now())
            ->whereDate('end_at', '>', Carbon::now())
            ->first();
        if ($seller_code != null) {
            if ($order->seller_code_id == null) {
                $code_discount = ($order->order_price * $seller_code->discount_percentage) / 100;
                $order->update([
//                    'order_price' => $order->order_price - $code_discount,
                    'total_price' => $order->total_price - $code_discount,
                    'seller_code_id' => $seller_code->id,
                    'discount_value' => $code_discount,
                    'order_price' => $order->order_price - $code_discount,
                ]);
                flash(trans('messages.seller_code_worked'))->success();
                return redirect()->back();
            } else {
                flash(trans('messages.seller_code_applied_before'))->error();
                return redirect()->back();
            }
        } else {
            flash(trans('messages.seller_code_not_working'))->error();
            return redirect()->back();
        }
    }

    public function empty_cart($id)
    {
        $order = TableOrder::findOrFail($id);
        $order->delete();
        return redirect()->back();
    }

    public function complete_order(Request $request)
    {
        $this->validate($request, [
            'table_code' => 'required_if:order_type,previous',
            'discount_name' => 'sometimes',
            'payment_method' => 'sometimes',
        ]);
        
        $order = TableOrder::find($request->order_id);
        if ($order == null) {
            flash(app()->getLocale() == 'ar' ? 'تم حذف الطلب لعدم إكماله في الوقت المحدد' : 'Order Is Deleted Because Time Left')->error();
            return redirect()->back();
        }
          // check if table belong to whatsapp
          $checkWhatsAppService = RestaurantOrderSetting::whereRestaurantId($order->table->restaurant_id)
          ->where('order_type', 'whatsapp')
          ->whereBranchId($order->table->branch_id)
          ->where('table', 'true')
          ->first();
      if ($checkWhatsAppService and $order->table->service_id == 9) {
          // check payment
          if (($request->payment_method and $request->payment_method == 'receipt_payment') or $request->payment_method == null) {
              // send to whatsapp
              $url = 'https://api.whatsapp.com/send?phone=' . $checkWhatsAppService->whatsapp_number . '&text=';
              $items = $order->order_items()->with('size', 'product', 'order_item_options.option')->get();
              $orderPrice = $order->order_price;
              $taxPrice = ($order->table->branch->tax == 'true' and $order->table->branch->tax_value > 0) ? (($order->table->branch->tax_value * $orderPrice) / 100) : 0;
              $content = '';
              foreach ($items as $index => $item):
                  $content .= '*' . ($index + 1) . '-  ' . $item->product->name . ' ' . $item->product_count . 'x* %0a';
                  $content .= 'السعر : ' . $item->price . ' %0a';
                  if (isset($item->size->id)):
                      $content .= 'الحجم : ' . $item->size->name;
                  endif;
                  if ($item->order_item_options->count() > 0) {
                      $content .= '%0a_الإضافات_%0a';
                  }
                  foreach ($item->order_item_options as $op):
                      $content .= $op->option->name . ' ' . $op->option_count . 'x ' . '%0a';
                  endforeach;

                  // $content .= '%0a';
              endforeach;

              if ($order->discount_value > 0)
                  $content .= '%0aالخصم : ' . $order->discount_value;
              // $content .= '%0aسعر الوجبات: ' .$order->order_price;
              $content .= '%0aقيمة الطلب : ' . $order->order_price;
              if ($taxPrice > 0):
                  $content .= '%0aالضريبة: ' . $order->tax;
              endif;
              $content .= '%0aإجمالي السعر: ' . $order->total_price;
              $content .= '%0a الطاولة : ' . $order->table->name_ar;
              $content .= '%0a الفرع : ' . $order->table->branch->name_ar;
              $order->delete();
              return redirect($url . $content);
          } elseif ($request->payment_method and $request->payment_method == 'online_payment') {
              if($request->payment_method == 'online') $order->update([
                  'payment_status' => false , 
              ]);
              if ($checkWhatsAppService->payment_company == 'tap') {
                  return redirect()->to(tap_payment($checkWhatsAppService->online_token, $order->total_price, 'test', 'test@email.com', '966', '050000000', 'tapRedirectBackTableOrder', $order->id));
              } elseif ($checkWhatsAppService->payment_company == 'express') {
                  $amount = number_format((float)$order->total_price, 2, '.', '');
                  return redirect()->to(express_payment($checkWhatsAppService->merchant_key, $checkWhatsAppService->express_password, $amount, 'table_express_success', $order->id, 'test', 'test@email.com'));
              } elseif ($checkWhatsAppService->payment_company == 'myFatoourah') {
                  if ($request->payment_type == 'visa') {
                      $charge = 2;
                  } elseif ($request->payment_type == 'mada') {
                      $charge = 6;
                  } elseif ($request->payment_type == 'apple_pay') {
                      $charge = 11;
                  } else {
                      $charge = 2;
                  }
                  $token = $checkWhatsAppService->online_token;
                  $amount = number_format((float)$order->total_price, 2, '.', '');
                  $data = array(
                      'PaymentMethodId' => $charge,
                      'CustomerName' => 'test',
                      'DisplayCurrencyIso' => 'SAR',
                      'MobileCountryCode' => '966',
                      'CustomerMobile' => '050000000',
                      'CustomerEmail' => 'test@email.com',
                      'InvoiceValue' => $amount,
                      'CallBackUrl' => route('checkTableStatus', [$order->id, $checkWhatsAppService->id]),
                      'ErrorUrl' => url('/error'),
                      'Language' => app()->getLocale(),
                      'CustomerReference' => 'ref 1',
                      'CustomerCivilId' => '12345678',
                      'UserDefinedField' => 'Custom field',
                      'ExpireDate' => '',
                      'CustomerAddress' => array(
                          'Block' => '',
                          'Street' => '',
                          'HouseBuildingNo' => '',
                          'Address' => '',
                          'AddressInstructions' => '',
                      ),
                      'InvoiceItems' => [array(
                          'ItemName' => 'item',
                          'Quantity' => '1',
                          'UnitPrice' => $amount,
                      )],
                  );
                  $data = json_encode($data);

                  $fatooraRes = MyFatoorah($token, $data);
                  $result = json_decode($fatooraRes);
                  // return $result;
                  if ($result->IsSuccess === true) {
                      return redirect()->to($result->Data->PaymentURL);
                  } else {
                      return redirect()->to(url('/error'));
                  }
              }
          }
      } else {
          
        if ($order->table->code != null) {
            if ($request->table_code == $order->table->code) {
                // check if table belong to whatsapp
                $checkWhatsAppService = RestaurantOrderSetting::whereRestaurantId($order->table->restaurant_id)
                    ->where('order_type', 'whatsapp')
                    ->where('table', 'true')
                    ->first();
                    
                if ($checkWhatsAppService and $order->table->service_id == 9) {
                    // send to whatsapp
                    $url = 'https://api.whatsapp.com/send?phone=' . $checkWhatsAppService->whatsapp_number . '&text=';
                    $items = $order->order_items()->with('size', 'product', 'order_item_options.option')->get();
                    $orderPrice = $order->order_price;
                    $taxPrice = ($order->table->branch->tax == 'true' and $order->table->branch->tax_value > 0) ? (($order->table->branch->tax_value * $orderPrice) / 100) : 0;
                    $content = '';
                    foreach ($items as $index => $item):
                        $content .= '*' . ($index + 1) . '-  ' . $item->product->name . ' ' . $item->product_count . 'x* %0a';
                        $content .= 'السعر : ' . $item->price . ' %0a';
                        if (isset($item->size->id)):
                            $content .= 'الحجم : ' . $item->size->name;
                        endif;
                        if ($item->order_item_options->count() > 0) {
                            $content .= '%0a_الإضافات_%0a';
                        }
                        foreach ($item->order_item_options as $op):
                            $content .= $op->option->name . ' ' . $op->option_count . 'x ' . '%0a';
                        endforeach;

                        // $content .= '%0a';
                    endforeach;

                    if ($order->discount_value > 0)
                        $content .= '%0aالخصم : ' . $order->discount_value;
                    // $content .= '%0aسعر الوجبات: ' .$order->order_price;
                    $content .= '%0aقيمة الطلب : ' . $order->order_price;
                    if ($taxPrice > 0):
                        $content .= '%0aالضريبة: ' . $order->tax;
                    endif;
                    $content .= '%0aإجمالي السعر: ' . $order->total_price;
                    $content .= '%0a الطاولة : ' . $order->table->name_ar;
                    $content .= '%0a الفرع : ' . $order->table->branch->name_ar;
                    $order->delete();
                    return redirect($url . $content);
                } else {
                    
                    if ($order->branch->foodics_status == 'true') {
                        $branch_id = $order->table->foodics_branch->foodics_id;
                        if ($request->discount_name != null) {
                            if ($order->order_items->count() > 0) {
                                foreach ($order->order_items as $item) {
                                    $discount = FoodicsDiscount::whereBranchId($order->branch->id)
                                        ->whereNameEn($request->discount_name)
                                        ->first();
                                    if ($discount) {
                                        checkTableProductDiscount($item->id, $discount->id);
                                    }
                                }
                            }
                        }
                        $count = $order->restaurant->foodics_orders + 1;
                        $order->restaurant->update([
                            'foodics_orders' => $count,
                        ]);
                        
                        create_foodics_table_order($order->restaurant_id, $branch_id, $order->order_items, 'EasyMenu-cash', $order->table_id , $order);
                    }
                    // complete order
                    $order->update([
                        'status' => 'new',
                    ]);
                    if ($order->branch->foodics_status == 'false') {
                        $orders = $order->restaurant->orders + 1;
                        $order->restaurant->update([
                            'orders' => $orders,
                        ]);
                    }
                }
                if($request->payment_method == 'online') $order->update([
                    'payment_status' => false , 
                ]);
            } else {
                // code error
                flash(app()->getLocale() == 'ar' ? 'كود الطاولة الذي ادخلتة غير صحيح' : 'Table Code You Entered Is Wrong')->error();
                return redirect()->back();
            }
        } else {
          
                if (($request->payment_method and $request->payment_method == 'receipt_payment') or $request->payment_method == null) {
                    
                    if ($order->branch->foodics_status == 'true' and false) {
                        $branch_id = $order->table->foodics_branch->foodics_id;
                        if ($request->discount_name != null) {
                            if ($order->order_items->count() > 0) {
                                foreach ($order->order_items as $item) {
                                    $discount = FoodicsDiscount::whereBranchId($order->branch->id)
                                        ->whereNameEn($request->discount_name)
                                        ->first();
                                    if ($discount) {
                                        checkTableProductDiscount($item->id, $discount->id);
                                    }
                                }
                            }
                        }
                        $count = $order->restaurant->foodics_orders + 1;
                        $order->restaurant->update([
                            'foodics_orders' => $count,
                        ]);
                        
                        create_foodics_table_order($order->restaurant_id, $branch_id, $order->order_items, 'EasyMenu-cash', $order->table_id);
                    }
                    // complete order
                    $order->update([
                        'status' => 'new',
                        'payment_type' => 'cash',
                    ]);
                    if ($order->branch->foodics_status == 'false') {
                        $orders = $order->restaurant->orders + 1;
                        $order->restaurant->update([
                            'orders' => $orders,
                        ]);
                    }
                } elseif ($request->payment_method and $request->payment_method == 'online_payment') {
                    if($request->payment_method == 'online') $order->update([
                        'payment_status' => false , 
                    ]);

                    if ($order->branch->foodics_status == 'true') {
                        if ($request->discount_name != null)
                        {
                            $order->update([
                                'discount_value' => $request->discount_name,
                            ]);
                        }
                        if ($order->branch and $order->payment_company == 'tap') {
                            return redirect()->to(tap_payment($order->branch->online_token, $order->total_price, 'test', 'test@email.com', '966', '050000000', 'tapRedirectBackTableOrder', $order->id));
                        } elseif ($order->branch and $order->branch->payment_company == 'express') {
                            $amount = number_format((float)$order->total_price, 2, '.', '');
                            return redirect()->to(express_payment($order->branch->merchant_key, $order->branch->express_password, $amount, 'table_express_success', $order->id, 'test', 'test@email.com'));
                        }
                        elseif ($order->branch and $order->branch->payment_company == 'myFatoourah') {
                            if ($request->payment_type == 'visa') {
                                $charge = 2;
                            } elseif ($request->payment_type == 'mada') {
                                $charge = 6;
                            } elseif ($request->payment_type == 'apple_pay') {
                                $charge = 11;
                            } else {
                                $charge = 2;
                            }
                            $token = $order->branch->online_token;
                            $amount = number_format((float)$order->total_price, 2, '.', '');
                            $data = array(
                                'PaymentMethodId' => $charge,
                                'CustomerName' => 'test',
                                'DisplayCurrencyIso' => 'SAR',
                                'MobileCountryCode' => '966',
                                'CustomerMobile' => '050000000',
                                'CustomerEmail' => 'test@email.com',
                                'InvoiceValue' => $amount,
                                'CallBackUrl' => route('checkTableStatus', [$order->id, $order->table->branch->id]),
                                'ErrorUrl' => url('/error'),
                                'Language' => app()->getLocale(),
                                'CustomerReference' => 'ref 1',
                                'CustomerCivilId' => '12345678',
                                'UserDefinedField' => 'Custom field',
                                'ExpireDate' => '',
                                'CustomerAddress' => array(
                                    'Block' => '',
                                    'Street' => '',
                                    'HouseBuildingNo' => '',
                                    'Address' => '',
                                    'AddressInstructions' => '',
                                ),
                                'InvoiceItems' => [array(
                                    'ItemName' => 'item',
                                    'Quantity' => '1',
                                    'UnitPrice' => $amount,
                                )],
                            );
                            $data = json_encode($data);
                            $fatooraRes = MyFatoorah($token, $data);
                            $result = json_decode($fatooraRes);
                            // file_put_contents(storage_path('app/my_fatoora.txt') , $fatooraRes);
                            if ($result->IsSuccess === true) {
                                return redirect()->to($result->Data->PaymentURL);
                            } else {
                                return redirect()->to(url('/error'));
                            }
                        }
                    }
                    // get easycasher setting
                    $checkEasyCasherSetting = RestaurantOrderSetting::whereRestaurantId($order->table->restaurant_id)
                        ->where('order_type', 'easymenu')
                        ->whereBranchId($order->table->branch_id)
                        ->where('table', 'true')
                        ->first();
                    if ($checkEasyCasherSetting and $checkEasyCasherSetting->payment_company == 'tap') {
                        return redirect()->to(tap_payment($checkEasyCasherSetting->online_token, $order->total_price, 'test', 'test@email.com', '966', '050000000', 'tapRedirectBackTableOrder', $order->id));
                    } elseif ($checkEasyCasherSetting and $checkEasyCasherSetting->payment_company == 'express') {
                        $amount = number_format((float)$order->total_price, 2, '.', '');
                        return redirect()->to(express_payment($checkEasyCasherSetting->merchant_key, $checkEasyCasherSetting->express_password, $amount, 'table_express_success', $order->id, 'test', 'test@email.com'));
                    }
                    elseif ($checkEasyCasherSetting and $checkEasyCasherSetting->payment_company == 'myFatoourah') {
                        if ($request->payment_type == 'visa') {
                            $charge = 2;
                        } elseif ($request->payment_type == 'mada') {
                            $charge = 6;
                        } elseif ($request->payment_type == 'apple_pay') {
                            $charge = 11;
                        } else {
                            $charge = 2;
                        }
                        $token = $checkEasyCasherSetting->online_token;
                        $amount = number_format((float)$order->total_price, 2, '.', '');
                        $data = array(
                            'PaymentMethodId' => $charge,
                            'CustomerName' => 'test',
                            'DisplayCurrencyIso' => 'SAR',
                            'MobileCountryCode' => '966',
                            'CustomerMobile' => '050000000',
                            'CustomerEmail' => 'test@email.com',
                            'InvoiceValue' => $amount,
                            'CallBackUrl' => route('checkTableStatus', [$order->id, $checkEasyCasherSetting->id]),
                            'ErrorUrl' => url('/error'),
                            'Language' => app()->getLocale(),
                            'CustomerReference' => 'ref 1',
                            'CustomerCivilId' => '12345678',
                            'UserDefinedField' => 'Custom field',
                            'ExpireDate' => '',
                            'CustomerAddress' => array(
                                'Block' => '',
                                'Street' => '',
                                'HouseBuildingNo' => '',
                                'Address' => '',
                                'AddressInstructions' => '',
                            ),
                            'InvoiceItems' => [array(
                                'ItemName' => 'item',
                                'Quantity' => '1',
                                'UnitPrice' => $amount,
                            )],
                        );
                        $data = json_encode($data);

                        $fatooraRes = MyFatoorah($token, $data);
                        $result = json_decode($fatooraRes);
                        // return $result;
                        if ($result->IsSuccess === true) {
                            return redirect()->to($result->Data->PaymentURL);
                        } else {
                            return redirect()->to(url('/error'));
                        }
                    }
                }
            }
        }

        flash(trans('messages.order_received_successfully'))->success();
        return redirect()->route('TableReceivedOrder', $order->id);
    }

    public function received_order($id = null)
    {
        $orders = [];
        if ($id !=null)
        {
            $order = TableOrder::findOrFail($id);
        }else{
            $order = TableOrder::orderBy('id' , 'desc')
                ->where('status' , '!=' , 'in_reservation')
                ->where('ip', Session::getId())
                ->firstOrFail();
            $orders = TableOrder::orderBy('id' , 'desc')
                ->where('status' , '!=' , 'in_reservation')
                ->where('ip', Session::getId())
                ->get();
        }
        $restaurant = $order->restaurant;
        $this->checkTheme($restaurant);
        $branch = $order->branch;
        $items = $order->order_items;
        $table = $order->table;
        // return $orders;
        if($id != null){
            return view('website.' . session('theme_path') . 'table.accessories.received_show', compact('order', 'table', 'items', 'restaurant', 'branch' , 'orders'));
        }
        return view('website.' . session('theme_path') . 'table.accessories.received', compact('order', 'table', 'items', 'restaurant', 'branch' , 'orders'));
    }

    public function removeTableOrderItem($id)
    {
        $item = TableOrderItem::findOrFail($id);
        $order = $item->table_order;
        /**
         * @calculate item price
         * 1- @check if order have options
         * 2- @calculate options and item price
         * 3- @decrease price from order
         * 4- @calcuate tax and decrease it
         */
        $options_price = 0;
        $tax = 0;
        if ($item->order_item_options) {
            foreach ($item->order_item_options as $option) {
                $options_price += $option->option_count * $option->option->price;
            }
        }
        $item_price = ($item->product_count * $item->price) + $options_price;
        if ($order->branch->tax == 'true' and $order->branch->tax_value > 0) {
            $tax = ($item_price * $order->branch->tax_value) / 100;
        }
        $order_price = $item->table_order->order_price - $item_price;
        $total_price = $item->table_order->total_price - ($item_price + $tax);
        $tax_value = $item->table_order->tax - $tax;
        $order->update([
            'order_price' => $order_price,
            'total_price' => $total_price,
            'tax' => $tax_value,
        ]);
        $item->delete();
        if ($order->order_items->count() == 0) {
            $order->delete();
        }
        $item->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->back();
    }

    public function table_order_tap(Request $request, $order_id, $token = null)
    {
        $input = $request->all();
        $tap_id = $input['tap_id'];
        $basURL = "https://api.tap.company/v2/charges/" . $tap_id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $basURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $order = TableOrder::find($request->order_id);
            $response = json_decode($response);
            if ($response->response->code == '000') {
                $checkWhatsAppService = RestaurantOrderSetting::whereRestaurantId($order->table->restaurant_id)
                    ->where('order_type', 'whatsapp')
                    ->whereBranchId($order->table->branch_id)
                    ->where('table', 'true')
                    ->first();
                $setting = RestaurantOrderSetting::whereRestaurantId($order->table->restaurant_id)
                    ->where('order_type', 'easymenu')
                    ->whereBranchId($order->table->branch_id)
                    ->where('table', 'true')
                    ->first();
                if ($checkWhatsAppService and $order->table->service_id == 9):
                    // send to whatsapp
                    $url = 'https://api.whatsapp.com/send?phone=' . $checkWhatsAppService->whatsapp_number . '&text=';
                    $items = $order->order_items()->with('size', 'product', 'order_item_options.option')->get();
                    $orderPrice = $order->order_price;
                    $taxPrice = ($order->table->branch->tax == 'true' and $order->table->branch->tax_value > 0) ? (($order->table->branch->tax_value * $orderPrice) / 100) : 0;
                    $content = '';
                    foreach ($items as $index => $item):
                        $content .= '*' . ($index + 1) . '-  ' . $item->product->name . ' ' . $item->product_count . 'x* %0a';
                        $content .= 'السعر : ' . $item->price . ' %0a';
                        if (isset($item->size->id)):
                            $content .= 'الحجم : ' . $item->size->name;
                        endif;
                        if ($item->order_item_options->count() > 0) {
                            $content .= '%0a_الإضافات_%0a';
                        }
                        foreach ($item->order_item_options as $op):
                            $content .= $op->option->name . ' ' . $op->option_count . 'x ' . '%0a';
                        endforeach;

                        // $content .= '%0a';
                    endforeach;

                    if ($order->discount_value > 0)
                        $content .= '%0aالخصم : ' . $order->discount_value;
                    // $content .= '%0aسعر الوجبات: ' .$order->order_price;
                    $content .= '%0aقيمة الطلب : ' . $order->order_price;
                    if ($taxPrice > 0):
                        $content .= '%0aالضريبة: ' . $order->tax;
                    endif;
                    $content .= '%0aإجمالي السعر: ' . $order->total_price;
                    $content .= '%0a الطاولة : ' . $order->table->name_ar;
                    $content .= '%0a الفرع : ' . $order->table->branch->name_ar;
                    $order->delete();
                    return redirect($url . $content);
                endif;
                if ($setting and $order->table->service_id == 10):
                    $order->update([
                        'status' => 'new',
                        'payment_type' => 'online',
                        'payment_status' => 'true' , 
                    ]);
                    if ($order->branch->foodics_status == 'false'):
                        $orders = $order->restaurant->orders + 1;
                        $order->restaurant->update([
                            'orders' => $orders,
                        ]);
                    endif;
                endif;
                if ($order->branch->foodics_status == 'true') {
                    $branch_id = $order->table->foodics_branch->foodics_id;
                    if ($order->discount_name != null) {
                        if ($order->order_items->count() > 0) {
                            foreach ($order->order_items as $item) {
                                $discount = FoodicsDiscount::whereBranchId($order->branch->id)
                                    ->whereNameEn($order->discount_name)
                                    ->first();
                                if ($discount) {
                                    checkTableProductDiscount($item->id, $discount->id);
                                }
                            }
                        }
                    }
                    $count = $order->restaurant->foodics_orders + 1;
                    $order->restaurant->update([
                        'foodics_orders' => $count,
                    ]);
                    create_foodics_table_order($order->restaurant_id, $branch_id, $order->order_items, 'EasyMenu-online', $order->table_id);
                    $order->update([
                        'status' => 'active',
                        'payment_type' => 'online',
                        'payment_status' => 'true' , 
                    ]);
                }
                flash(trans('messages.order_received_successfully'))->success();
                return redirect()->route('TableReceivedOrder', $order->id);

            }
        }
    }

    public function express_success(Request $request , $order_id)
    {
        $order = TableOrder::find($order_id);
        $checkWhatsAppService = RestaurantOrderSetting::whereRestaurantId($order->table->restaurant_id)
            ->where('order_type', 'whatsapp')
            ->whereBranchId($order->table->branch_id)
            ->where('table', 'true')
            ->first();
        $setting = RestaurantOrderSetting::whereRestaurantId($order->table->restaurant_id)
            ->where('order_type', 'easymenu')
            ->whereBranchId($order->table->branch_id)
            ->where('table', 'true')
            ->first();
        if ($checkWhatsAppService and $order->table->service_id == 9):
            // send to whatsapp
            $url = 'https://api.whatsapp.com/send?phone=' . $checkWhatsAppService->whatsapp_number . '&text=';
            $items = $order->order_items()->with('size', 'product', 'order_item_options.option')->get();
            $orderPrice = $order->order_price;
            $taxPrice = ($order->table->branch->tax == 'true' and $order->table->branch->tax_value > 0) ? (($order->table->branch->tax_value * $orderPrice) / 100) : 0;
            $content = '';
            foreach ($items as $index => $item):
                $content .= '*' . ($index + 1) . '-  ' . $item->product->name . ' ' . $item->product_count . 'x* %0a';
                $content .= 'السعر : ' . $item->price . ' %0a';
                if (isset($item->size->id)):
                    $content .= 'الحجم : ' . $item->size->name;
                endif;
                if ($item->order_item_options->count() > 0) {
                    $content .= '%0a_الإضافات_%0a';
                }
                foreach ($item->order_item_options as $op):
                    $content .= $op->option->name . ' ' . $op->option_count . 'x ' . '%0a';
                endforeach;

                // $content .= '%0a';
            endforeach;

            if ($order->discount_value > 0)
                $content .= '%0aالخصم : ' . $order->discount_value;
            // $content .= '%0aسعر الوجبات: ' .$order->order_price;
            $content .= '%0aقيمة الطلب : ' . $order->order_price;
            if ($taxPrice > 0):
                $content .= '%0aالضريبة: ' . $order->tax;
            endif;
            $content .= '%0aإجمالي السعر: ' . $order->total_price;
            $content .= '%0a الطاولة : ' . $order->table->name_ar;
            $content .= '%0a الفرع : ' . $order->table->branch->name_ar;
            $order->delete();
            return redirect($url . $content);
        endif;
        if ($setting and $order->table->service_id == 10):
            $order->update([
                'status' => 'new',
                'payment_type' => 'online',
                'payment_status' => 'true'
            ]);
            if ($order->branch->foodics_status == 'false'):
                $orders = $order->restaurant->orders + 1;
                $order->restaurant->update([
                    'orders' => $orders,
                ]);
            endif;
        endif;
        if ($order->branch->foodics_status == 'true') {
            $branch_id = $order->table->foodics_branch->foodics_id;
            if ($order->discount_name != null) {
                if ($order->order_items->count() > 0) {
                    foreach ($order->order_items as $item) {
                        $discount = FoodicsDiscount::whereBranchId($order->branch->id)
                            ->whereNameEn($order->discount_name)
                            ->first();
                        if ($discount) {
                            checkTableProductDiscount($item->id, $discount->id);
                        }
                    }
                }
            }
            $count = $order->restaurant->foodics_orders + 1;
            $order->restaurant->update([
                'foodics_orders' => $count,
            ]);
            create_foodics_table_order($order->restaurant_id, $branch_id, $order->order_items, 'EasyMenu-online', $order->table_id);
            $order->update([
                'status' => 'active',
                'payment_type' => 'online',
                'payment_status' => 'true'
            ]);
        }
        flash(trans('messages.order_received_successfully'))->success();
        return redirect()->route('TableReceivedOrder', $order->id);
    }

    public function check_status(Request $request, $order_id, $id = null)
    {
        $order = TableOrder::find($order_id);
        $setting = RestaurantOrderSetting::find($id);
        $token = $setting != null ? $setting->online_token : $order->branch->online_token;
        $PaymentId = \Request::query('paymentId');

        $resData = MyFatoorahStatus($token, $PaymentId);
            file_put_contents(storage_path('app/my_fatoora_status.txt') , $resData);
        $result = json_decode($resData);

        if (isset($result->IsSuccess) and $result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid") {
            $InvoiceId = $result->Data->InvoiceId;
            $order->update([
                'payment_type' => 'online',
                'invoice_id'   => $InvoiceId,
            ]);
            if ($order->branch->foodics_status == 'true') {
                $branch_id = $order->table->foodics_branch->foodics_id;
                if ($order->discount_name != null) {
                    if ($order->order_items->count() > 0) {
                        foreach ($order->order_items as $item) {
                            $discount = FoodicsDiscount::whereBranchId($order->branch->id)
                                ->whereNameEn($order->discount_name)
                                ->first();
                            if ($discount) {
                                checkTableProductDiscount($item->id, $discount->id);
                            }
                        }
                    }
                }
                $count = $order->restaurant->foodics_orders + 1;
                $order->restaurant->update([
                    'foodics_orders' => $count,
                ]);
                create_foodics_table_order($order->restaurant_id, $branch_id, $order->order_items, 'EasyMenu-online', $order->table_id);
                $order->update([
                    'status' => 'active' , 
                    'payment_type' => 'online' , 
                    'payment_status' => 'true' , 
                ]);
            }
             if ($setting and $order->table->service_id == 10):
                $order->update([
                    'status' => 'new',
                    'payment_type' => 'online',
                ]);
                if ($order->branch->foodics_status == 'false'):
                    $orders = $order->restaurant->orders + 1;
                    $order->restaurant->update([
                        'orders' => $orders,
                    ]);
                endif;
            endif;
            if ($setting and $order->table->service_id == 9):
                // send to whatsapp
                $url = 'https://api.whatsapp.com/send?phone=' . $setting->whatsapp_number . '&text=';
                $items = $order->order_items()->with('size', 'product', 'order_item_options.option')->get();
                $orderPrice = $order->order_price;
                $taxPrice = ($order->table->branch->tax == 'true' and $order->table->branch->tax_value > 0) ? (($order->table->branch->tax_value * $orderPrice) / 100) : 0;
                $content = '';
                foreach ($items as $index => $item):
                    $content .= '*' . ($index + 1) . '-  ' . $item->product->name . ' ' . $item->product_count . 'x* %0a';
                    $content .= 'السعر : ' . $item->price . ' %0a';
                    if (isset($item->size->id)):
                        $content .= 'الحجم : ' . $item->size->name;
                    endif;
                    if ($item->order_item_options->count() > 0) {
                        $content .= '%0a_الإضافات_%0a';
                    }
                    foreach ($item->order_item_options as $op):
                        $content .= $op->option->name . ' ' . $op->option_count . 'x ' . '%0a';
                    endforeach;

                    // $content .= '%0a';
                endforeach;

                if ($order->discount_value > 0)
                    $content .= '%0aالخصم : ' . $order->discount_value;
                // $content .= '%0aسعر الوجبات: ' .$order->order_price;
                $content .= '%0aقيمة الطلب : ' . $order->order_price;
                if ($taxPrice > 0):
                    $content .= '%0aالضريبة: ' . $order->tax;
                endif;
                $content .= '%0aإجمالي السعر: ' . $order->total_price;
                $content .= '%0a الطاولة : ' . $order->table->name_ar;
                $content .= '%0a الفرع : ' . $order->table->branch->name_ar;
                $order->delete();
                return redirect($url . $content);
            endif;
           
         
            flash(trans('messages.order_received_successfully'))->success();
            return redirect()->route('TableReceivedOrder', $order->id);
        }
    }


}
