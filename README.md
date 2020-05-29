# Formation Laravel

## App/
    
      
      
      <?php

      namespace App;

      use Illuminate\Database\Eloquent\Model;

      class Event extends Model
      {
          // Champs pouvant être remplir
          protected $fillable = ['name', 'description', 'location', 'price', 'starts_at']; 
          
          // Chmaps de type carbone
          protected $dates = ['starts_at'];
          
          // Champs & type 
          protected $cats = [
              'starts_at'=>'datetime',
              'price'=>'float'
          ];
          
          //  Accesseur fake_price
          public function getFakePriceAttribute($value)
          {
            return $this->attributes['price'] + 100;
          }
          

          public function isFree()
          {
              return $this->price == 0;
          }
      }
