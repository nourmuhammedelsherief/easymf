<?php

namespace App\Models;

use App\Models\Reservation\ReservationBranch;
use App\Models\Reservation\ReservationTable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Restaurant extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'restaurant';
    protected $table = 'restaurants';
    protected $fillable = [
        'restaurant_id',
        'name_ar',
        'name_en',
        'country_id',
        'city_id',
        'package_id',
        'phone_number',
        'email',
        'password',
        'phone_verification',
        'latitude',
        'longitude',
        'status',     //ENUM( 'inComplete','tentative','active','finished' ),
        'logo',
        'ar',    // true , false
        'en',    // true , false
        'archive',    // true , false
        'cart',    // true , false
        'name_barcode' , // the name used for barcode,
        'menu_arrange',
        'product_arrange',
        'total_tax_price',
        'tax_value',
        'tax',
        'tax_foodics_id',
        'foodics_charge_id',
        'information_ar',
        'information_en',
        'description_ar',
        'description_en',
        'views',
        'myfatoora_token',
        'show_branches_list',
        'menu',        // ENUM('vertical','horizontal'),
        'state',       // ENUM('open','closed','busy' , 'un_available')
        'enable_feedback',
        'foodics_status', // Emun (true,false)
        'foodics_state',
        'foodics_access_token', // text
        'delivery_price',     // delivery price used for foodics deliveries
        'enable_bank', // Emun (true,false)
        'enable_reservation_online_pay' , 'enable_reservation_bank',
        'enable_online_payment', // Emun (true,false)
        'reservation_description_ar'  ,
        'reservation_description_en' ,
        'reservation_service',     // true , false
        'answer_id',
        'orders',
        'foodics_orders',
        'whatsapp_orders',
        'enable_fixed_category'  ,  //true , false
        'enable_contact_us',  //true , false
        'enable_reservation_cash' , //true , false
        'default_lang' ,
        'is_call_phone' , //true , false
        'is_whatsapp' , //true , false
        'call_phone' ,
        'whatsapp_number' ,
        'payment_company',
        'online_token',
        'theme_id',
        'archive_category_id',
        'merchant_key',
        'express_password',
        'stop_menu',
        'admin_activation',
        'type',   // restaurant , employee
        'online_payment_fees' ,
        'last_session',
        'reservation_to_restaurant' , // true , false
        'last_activity' ,
        'reservation_call_number' , 'reservation_is_call_phone' , 'reservation_whatsapp_number' , 'reservation_tax_value' , 'reservation_is_whatsapp' , 'reservation_tax',
        'enable_loyalty_point' , // true, false
        'enable_loyalty_point_paymet_method' , //true , false
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getImagePathAttribute(){
        return 'uploads/restaurants/logo/' . $this->logo;
    }
    public function getNameAttribute(){
        return app()->getLocale() == 'en' ? $this->name_en : $this->name_ar;
    }
    public function getDescriptionAttribute(){
        return app()->getLocale() == 'en' ? $this->description_en : $this->description_ar;
    }

    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class , 'city_id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class , 'package_id');
    }
    public function archiveCategory()
    {
        return $this->belongsTo(ArchiveCategory::class , 'archive_category_id');
    }
    public function subscription()
    {
        return $this->hasOne(Subscription::class , 'restaurant_id');
    }
    public function marketerOperations(){
        return $this->hasMany(MarketerOperation::class , 'restaurant_id');
    }

    public function color()
    {
        return $this->hasOne(RestaurantColors::class , 'restaurant_id');
    }
    public function menu_categories()
    {
        return $this->hasMany(MenuCategory::class , 'restaurant_id');
    }
    public function restaurantCategories(){
        return $this->belongsToMany(Category::class , 'restaurant_categories'  , 'restaurant_id' , 'category_id');
    }
    public function branches()
    {
        return $this->hasMany(Branch::class , 'restaurant_id');
    }
    public function tables()
    {
        return $this->hasMany(Table::class , 'restaurant_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class , 'restaurant_id');
    }
    public function deliveries()
    {
        return $this->hasMany(RestaurantDelivery::class , 'restaurant_id');
    }
    public function socials()
    {
        return $this->hasMany(RestaurantSocial::class , 'restaurant_id');
    }
    public function sensitivities()
    {
        return $this->hasMany(RestaurantSensitivity::class , 'restaurant_id');
    }
    public function offers()
    {
        return $this->hasMany(RestaurantOffer::class , 'restaurant_id');
    }
    public function sliders()
    {
        return $this->hasMany(RestaurantSlider::class , 'restaurant_id');
    }
    public function res_branches()
    {
        return $this->hasMany(RestaurantBranch::class , 'restaurant_id');
    }
    public function notes()
    {
        return $this->hasMany(AdminNote::class , 'restaurant_id');
    }

    public function rateBranches(){
        return $this->hasMany(RestaurantFeedbackBranch::class , 'restaurant_id');
    }


    public function serviceSubscriptions(){
        return $this->hasMany(ServiceSubscription::class);
    }
    public function banks(){
        return $this->hasMany(Bank::class , 'restaurant_id');
    }

    public function reservationBranches(){
        return $this->hasMany(ReservationBranch::class , 'restaurant_id');
    }

    public function reservationTables(){
        return $this->hasMany(ReservationTable::class , 'restaurant_id');
    }

    public function histories(){
        return $this->hasMany(History::class , 'restaurant_id');
    }

    public function posters(){
        return $this->hasMany(RestaurantPoster::class , 'restaurant_id');
    }
    public function answer()
    {
        return $this->belongsTo(RegisterAnswers::class , 'answer_id');
    }
    public function contactUsItems(){
        return $this->hasMany(RestaurantContactUs::class , 'restaurant_id');
    }
    public function reports()
    {
        return $this->hasMany(Report::class , 'restaurant_id');
    }
    public function theme(){
        return $this->belongsTo(Theme::class , 'theme_id');
    }
    public function controls()
    {
        return $this->hasMany(RestaurantControl::class , 'restaurant_id');
    }
    public function loyaltyPointPrices(){
        return $this->hasMany(LoyaltyPointPrice::class , 'restaurant_id');
    }
}
