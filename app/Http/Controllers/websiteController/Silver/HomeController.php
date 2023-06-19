<?php

namespace App\Http\Controllers\websiteController\Silver;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\MenuCategory;
use App\Models\Modifier;
use App\Models\Product;
use App\Models\ProductModifier;
use App\Models\Restaurant;
use App\Models\RestaurantContactUsLink;
use App\Models\RestaurantDelivery;
use App\Models\RestaurantSocial;
use App\Models\RestaurantView;
use App\Models\ServiceSubscription;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{

    public function index(Request $request, $name, $category_id = null, $branch_name = null, $subCat = null)
    {
        
        $productsLimit = 50;
        $showMainPage = true;
        $restaurant = Restaurant::where('name_barcode', $name)
            ->whereIn('status', ['active', 'tentative'])
            ->where('archive', 'false')
            ->firstOrFail();
        // return session()->all();
        $this->checkTheme($restaurant);
        if ($restaurant) {
            if ($branch_name != null) {
                // $showMainPage = false;
                $branch = Branch::where('name_barcode', $branch_name)
                    ->where('restaurant_id', $restaurant->id)
                    ->whereIn('status', ['active', 'tentative'])
                    ->where('main', 'false')
                    ->first();
                if ($branch == null) {

                    $branch = Branch::where('name_barcode', $branch_name)
                        ->where('restaurant_id', $restaurant->id)
                        ->whereIn('status', ['active', 'tentative'])
                        ->where('main', 'true')
                        ->firstOrFail();
                }
                if (isset($branch->id) and $branch->main == 'true') {
                    if ($restaurant->status == 'finished' || $restaurant->subscription->status == 'tentative_finished') {
                        $table = null;
                        return view('error', compact('restaurant', 'table', 'branch'));
                    }
                }
            } else {
                $branch = Branch::where('restaurant_id', $restaurant->id)
                    ->where('main', 'true')
                    //    ->where('status' , 'active')
                    ->first();
                if ($restaurant->status == 'finished' || $restaurant->subscription->status == 'tentative_finished') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                }
            }

            if ($category_id != null) {
                $showMainPage = false;
                $menu_category = MenuCategory::where('menu_categories.id', $category_id)->firstOrFail();
                if ($subCat != null) {

                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('sub_category_id', $subCat)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate($productsLimit);
                } else {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate($productsLimit);
                }
            } else {
                $menu_category = MenuCategory::whereRestaurantId($restaurant->id)
                    ->where('branch_id', $branch->id)
                    ->where('active', 'true')
                    ->where('time', 'false')
                    ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                    // ->orderBy('menu_categories.id' , 'asc')
                    ->first();
                if ($subCat != null) {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('sub_category_id', $subCat)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate($productsLimit);
                } else {
                    if ($menu_category != null) {
                        $products = Product::whereRestaurantId($restaurant->id)
                            ->where('branch_id', $branch->id)
                            ->where('menu_category_id', $menu_category->id)
                            ->where('active', 'true')
                            ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                            ->paginate($productsLimit);
                    } else {
                        $products = Product::whereRestaurantId($restaurant->id)
                            ->where('branch_id', $branch->id)
                            ->where('active', 'true')
                            ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                            ->paginate($productsLimit);
                    }
                }

                $restaurant->update([
                    'views' => $restaurant->views + 1,
                ]);

                $view = RestaurantView::whereDate('created_at', Carbon::today())
                    ->whereRestaurantId($restaurant->id)
                    ->first();
                if ($view) {
                    $view->update([
                        'views' => $view->views + 1,
                    ]);
                } else {
                    // create new one
                    RestaurantView::create([
                        'restaurant_id' => $restaurant->id,
                        'views' => 1
                    ]);
                }
            }

            $table = null;
            if ($restaurant->stop_menu == 'true') {
                $table = null;
                return view('error', compact('restaurant', 'table', 'branch'));
            }

            // api when select category
            // return $request->all();
            if (isset($menu_category->id)):
                session()->put('product_restaurant_id', $restaurant->id);
                session()->put('product_back_to', route('sliverHome', [$restaurant->name_barcode, $menu_category->id]));
            endif;
            if ($request->wantsJson() and !empty($category_id) and $request->has('is_category')):
                return response([
                    'status' => true,
                    'data' => [
                        'category_name' => $menu_category->name,

                        'sub_categories' => view('website.' . session('theme_path') . 'silver.accessories.sub_categories', compact([['restaurant', 'table', 'menu_category', 'products', 'branch', 'subCat']]))->render(),

                        'products' => view('website.' . session('theme_path') . 'silver.accessories.products', compact(['restaurant', 'table', 'menu_category', 'products', 'branch', 'subCat']))->render(),

                        'ads_content' => view('website.' . session('theme_path') . 'silver.accessories.ads_popup', compact(['restaurant', 'table', 'menu_category', 'products', 'branch', 'subCat']))->render()
                    ],
                ]);
            endif;
            // return $products;

            return view('website.' . session('theme_path') . 'silver.index', compact('restaurant', 'table', 'menu_category', 'products', 'branch', 'subCat', 'showMainPage'));
        } else {
            $restaurant = Restaurant::where('name_barcode', $name)
                ->first();
            if ($restaurant) {
                $branch = Branch::whereRestaurantId($restaurant->id)
                    ->where('main', 'true')
                    ->first();
                $table = null;
                return view('error', compact('restaurant', 'table', 'branch'));
            }
        }
    }

    public function index_table(Request $request, $name, $table_barcodeName = null, $category_id = null, $branch_name = null, $subCat = null)
    {
        $productsLimit = 50;
        $showMainPage = true;
        $restaurant = Restaurant::where('name_barcode', $name)
            ->whereIn('status', ['active', 'tentative'])
            ->where('archive', 'false')
            ->firstOrFail();
        $this->checkTheme($restaurant);

        // check if table has foodics
        $check_foodics_table = Table::whereFoodics_id($table_barcodeName)
            ->whereRestaurant_id($restaurant->id)
            ->first();
        if ($check_foodics_table != null) {
            $table = $check_foodics_table;
        } else {
            $table = Table::whereNameBarcode($table_barcodeName)
                ->whereRestaurant_id($restaurant->id)
                ->firstOrFail();
        }
        if ($restaurant) {
            if ($branch_name != null) {
                // $showMainPage = false;
                $branch = Branch::where('name_barcode', $branch_name)
                    ->where('restaurant_id', $restaurant->id)
                    ->whereIn('status', ['active', 'tentative'])
                    ->where('main', 'false')
                    ->first();
                if ($branch == null) {
                    $branch = Branch::where('name_barcode', $branch_name)
                        ->where('restaurant_id', $restaurant->id)
                        ->whereIn('status', ['active', 'tentative'])
                        ->where('main', 'true')
                        ->first();
                }
                if ($branch->main == 'true') {
                    if ($restaurant->status == 'finished' || $restaurant->subscription->status == 'tentative_finished') {
                        $table = null;
                        return view('error', compact('restaurant', 'table', 'branch'));
                    }
                }
            } else {
                $branch = Branch::where('restaurant_id', $restaurant->id)
                    ->where('main', 'true')
                    //    ->where('status' , 'active')
                    ->first();
                if ($restaurant->status == 'finished' || $restaurant->subscription->status == 'tentative_finished') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                }
            }

            if ($category_id != null) {
                $showMainPage = false;
                $menu_category = MenuCategory::findOrFail($category_id);
                if ($subCat != null) {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('sub_category_id', $subCat)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate($productsLimit);
                } else {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate($productsLimit);
                }
            } else {
                $menu_category = MenuCategory::whereRestaurantId($restaurant->id)
                    ->where('branch_id', $branch->id)
                    ->where('active', 'true')
                    ->where('time', 'false')
                    ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                    ->orderBy('id', 'asc')
                    ->first();
                if ($subCat != null) {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('sub_category_id', $subCat)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate($productsLimit);
                } else {
                    if ($menu_category != null) {
                        $products = Product::whereRestaurantId($restaurant->id)
                            ->where('branch_id', $branch->id)
                            ->where('menu_category_id', $menu_category->id)
                            ->where('active', 'true')
                            ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                            ->paginate($productsLimit);
                    } else {
                        $products = Product::whereRestaurantId($restaurant->id)
                            ->where('branch_id', $branch->id)
                            ->where('active', 'true')
                            ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                            ->paginate($productsLimit);
                    }
                }

                $restaurant->update([
                    'views' => $restaurant->views + 1,
                ]);

                $view = RestaurantView::whereDate('created_at', Carbon::today())
                    ->whereRestaurantId($restaurant->id)
                    ->first();
                if ($view) {
                    $view->update([
                        'views' => $view->views + 1,
                    ]);
                } else {
                    // create new one
                    RestaurantView::create([
                        'restaurant_id' => $restaurant->id,
                        'views' => 1
                    ]);
                }
            }

            if ($request->wantsJson() and !empty($category_id) and $request->has('is_category')):
                return response([
                    'status' => true,
                    'data' => [
                        'category_name' => $menu_category->name,
                        'sub_categories' => view('website.' . session('theme_path') . 'silver.accessories.sub_categories', compact([['restaurant', 'table', 'menu_category', 'products', 'branch', 'subCat']]))->render(),
                        'products' => view('website.' . session('theme_path') . 'silver.accessories.products', compact(['restaurant', 'table', 'menu_category', 'products', 'branch', 'subCat']))->render(),
                        'ads_content' => view('website.' . session('theme_path') . 'silver.accessories.ads_popup', compact(['restaurant', 'table', 'menu_category', 'products', 'branch', 'subCat']))->render()
                    ],
                ]);
            endif;
            // if($restaurant->id == 1194) return $table;
            if (isset($menu_category->id)):
                session()->put('product_restaurant_id', $restaurant->id);
                session()->put('product_back_to', route('sliverHome', [$restaurant->name_barcode, $menu_category->id]));
            endif;
            return view('website.' . session('theme_path') . 'silver.index', compact('restaurant', 'table', 'menu_category', 'products', 'branch', 'subCat', 'showMainPage'));
        } else {
            $restaurant = Restaurant::where('name_barcode', $name)
                ->first();
            if ($restaurant) {
                $branch = Branch::whereRestaurantId($restaurant->id)
                    ->where('main', 'true')
                    ->first();
                $table = null;
                return view('error', compact('restaurant', 'table', 'branch'));
            }
        }
    }

    public function branch_index($name, $branch_name = null, $category_id = null, $subCat = null)
    {
        $restaurant = Restaurant::where('name_barcode', $name)->first();
        $this->checkTheme($restaurant);
        if ($restaurant) {
            if ($branch_name != null) {
                $branch = Branch::where('name_barcode', $branch_name)
                    ->where('restaurant_id', $restaurant->id)
                    ->where('main', 'false')
                    ->first();
                if ($branch == null) {
                    $branch = Branch::where('name_barcode', $branch_name)
                        ->where('restaurant_id', $restaurant->id)
                        ->where('main', 'true')
                        ->first();
                    if ($branch == null) {
                        abort(404);
                    }
                }
                if ($restaurant->stop_menu == 'true') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                }
                if ($branch->status == 'not_active' or $branch->status == 'tentative_finished' or $branch->status == 'finished') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                } elseif ($branch->archive == 'true') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                } elseif ($branch->stop_menu == 'true') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                }
                if ($branch->main == 'true') {
                    if ($restaurant->status == 'finished' || $restaurant->subscription->status == 'tentative_finished') {
                        $table = null;
                        return view('error', compact('restaurant', 'table', 'branch'));
                    }
                }
            } else {
                $branch = Branch::where('restaurant_id', $restaurant->id)
                    ->where('main', 'true')
                    ->first();
                if ($restaurant->status == 'finished' || $branch->status == 'not_active' || $restaurant->subscription->status == 'tentative_finished') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                }
            }
            if ($category_id != null) {
                $menu_category = MenuCategory::findOrFail($category_id);
                if ($subCat != null) {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('sub_category_id', $subCat)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate(5);
                } else {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate(5);
                }
            } else {
                $menu_category = MenuCategory::whereRestaurantId($restaurant->id)
                    ->where('branch_id', $branch->id)
                    ->where('active', 'true')
                    ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                    ->orderBy('id', 'asc')
                    ->first();
                if ($subCat != null) {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('sub_category_id', $subCat)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate(5);
                } else {
                    if ($menu_category != null) {
                        $products = Product::whereRestaurantId($restaurant->id)
                            ->where('branch_id', $branch->id)
                            ->where('menu_category_id', $menu_category->id)
                            ->where('active', 'true')
                            ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                            ->paginate(5);
                    } else {
                        $products = Product::whereRestaurantId($restaurant->id)
                            ->where('branch_id', $branch->id)
                            ->where('active', 'true')
                            ->where('menu_category_id', null)
                            ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                            ->paginate(5);
                    }
                }
                $branch->update([
                    'views' => $branch->views + 1,
                ]);
            }
            if (isset($menu_category->id)):
                session()->put('product_restaurant_id', $restaurant->id);
                session()->put('product_back_to', route('sliverHome', [$restaurant->name_barcode, $menu_category->id]));
            endif;
            $table = null;
            return view('website.' . session('theme_path') . 'silver.index', compact('restaurant', 'table', 'menu_category', 'products', 'branch', 'subCat'));
        }
    }

    public function index_table_branch($name, $table_barcodeName = null, $branch_name = null, $category_id = null, $subCat = null)
    {
        $restaurant = Restaurant::where('name_barcode', $name)->firstOrFail();
        $this->checkTheme($restaurant);
        $showMainPage = true;
        // check if table has foodics
        $check_foodics_table = Table::whereFoodics_id($table_barcodeName)
            ->whereRestaurant_id($restaurant->id)
            ->first();
        if ($check_foodics_table != null) {
            $table = $check_foodics_table;
        } else {
            $table = Table::whereNameBarcode($table_barcodeName)
                ->whereRestaurant_id($restaurant->id)
                ->firstOrFail();
        }
        if ($restaurant) {
            if ($branch_name != null) {
                // $showMainPage = false;
                $branch = Branch::where('name_barcode', $branch_name)
                    ->where('restaurant_id', $restaurant->id)
                    ->where('main', 'false')
                    ->first();
                if ($branch == null) {
                    $branch = Branch::where('name_barcode', $branch_name)
                        ->where('restaurant_id', $restaurant->id)
                        ->where('main', 'true')
                        ->firstOrFail();
                }
                if ($branch->status == 'not_active') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                } elseif ($branch->archive == 'true') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                } elseif ($branch->stop_menu == 'true') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                }
                if ($branch->main == 'true') {
                    if ($restaurant->status == 'finished' || $restaurant->subscription->status == 'tentative_finished') {
                        $table = null;
                        return view('error', compact('restaurant', 'table', 'branch'));
                    }
                }
            } else {
                $branch = Branch::where('restaurant_id', $restaurant->id)
                    ->where('main', 'true')
                    ->first();
                if ($restaurant->status == 'finished' || $branch->status == 'not_active' || $restaurant->subscription->status == 'tentative_finished') {
                    $table = null;
                    return view('error', compact('restaurant', 'table', 'branch'));
                }
            }
            // check if service available or not
            if ($table->service_id != null):
                $service_subscription = ServiceSubscription::whereRestaurantId($branch->restaurant_id)
                    ->whereBranchId($branch->id)
                    ->whereServiceId($table->service_id)
                    ->first();
                if ($service_subscription->status != 'tentative' and $service_subscription->status != 'active'):
                    abort(404);
                endif;
            endif;
            if ($category_id != null) {
                $showMainPage = false;
                $menu_category = MenuCategory::findOrFail($category_id);
                if ($subCat != null) {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('sub_category_id', $subCat)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate(5);
                } else {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate(5);
                }
            } else {
                $menu_category = MenuCategory::whereRestaurantId($restaurant->id)
                    ->where('branch_id', $branch->id)
                    ->where('active', 'true')
                    ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                    ->orderBy('id', 'asc')
                    ->first();
                if ($subCat != null) {
                    $products = Product::whereRestaurantId($restaurant->id)
                        ->where('branch_id', $branch->id)
                        ->where('menu_category_id', $menu_category->id)
                        ->where('sub_category_id', $subCat)
                        ->where('active', 'true')
                        ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                        ->paginate(5);
                } else {
                    if ($menu_category != null) {
                        $products = Product::whereRestaurantId($restaurant->id)
                            ->where('branch_id', $branch->id)
                            ->where('menu_category_id', $menu_category->id)
                            ->where('active', 'true')
                            ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                            ->paginate(5);
                    } else {
                        $products = Product::whereRestaurantId($restaurant->id)
                            ->where('branch_id', $branch->id)
                            ->where('active', 'true')
                            ->where('menu_category_id', null)
                            ->orderBy(DB::raw('ISNULL(arrange), arrange'), 'ASC')
                            ->paginate(5);
                    }
                }
                $branch->update([
                    'views' => $branch->views + 1,
                ]);
            }

            return view('website.' . session('theme_path') . 'silver.index', compact('restaurant', 'table', 'menu_category', 'products', 'branch', 'subCat', 'showMainPage'));
        }
    }


    public function lang($locale)
    {
        Artisan::call('optimize:clear');
        session(['locale' => $locale]);
        App::setLocale($locale);
        return redirect()->previous();
    }

    public function loadMenuProduct($id, $table_id = null)
    {
        if ($table_id != null) {
            $table = Table::find($table_id);
        } else {
            $table = null;
        }
        $meal = Product::findOrFail($id);
        $main_additions = ProductModifier::whereProductId($meal->id)
            ->orderBy('id', 'desc')
            ->get();
        return view('website.' . session('theme_path') . 'silver.accessories.menu_product', compact('meal', 'table', 'main_additions'));
    }

    public function storeProductWeb(Request $request)
    {
        return redirect()->back();
    }

    public function contactUsPage(Request $request, $name, $item = null)
    {

        $restaurant = Restaurant::where('name_barcode', $name)->where('enable_contact_us', 'true')->firstOrFail();
        $branch = $restaurant->branches()->where('main', 'true')->first();
        $contact = null;
        if (!empty($item)):
            $contact = RestaurantContactUsLink::where('status', 'true')->where('restaurant_id', $restaurant->id)->where('barcode', $item)->first();
        endif;
        $this->checkTheme($restaurant);
        $isContactUs = true;
        return view('website.' . session('theme_path') . 'contact_us', compact('restaurant', 'branch', 'contact' , 'isContactUs'));
    }

    public function productDetails(Request $request, $name, Product $product, $table_id = null)
    {
        $restaurant = Restaurant::where('name_barcode', $name)
            ->whereIn('status', ['active', 'tentative'])
            ->where('archive', 'false')
            ->firstOrFail();
        $this->checkTheme($restaurant);
        if (!$restaurant->products()->where('id', $product->id)):
            abort(404);
        endif;
        $meal = $product;
        $branch = $product->branch;
        $table = null;
        if ($table_id != null) {
            $table = Table::find($table_id);
        } else {
            $table = null;
        }

        $main_additions = ProductModifier::whereProductId($meal->id)
            ->orderBy('id', 'desc')
            ->get();
        $meal = $product;

        if (empty(session('product_back_to')) or empty(session('product_restaurant_id')) or session('product_restaurant_id') != $product->restaurant_id) {
            session()->put('product_restaurant_id', $product->restaurant_id);
            session()->put('product_back_to', route('sliverHome', [$restaurant->name_barcode, $product->menu_category_id]));
        }

        return view('website.' . session('theme_path') . 'silver.accessories.xproduct_details', compact('restaurant', 'meal', 'table', 'main_additions', 'branch'));
    }
}
