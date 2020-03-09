# Model Filtrable

### Installation

```bash
$ composer require jeidison/model-filtrable
```
# Usage

#### Add Trait in model


```php
...

use Jeidison\Filtrable\Filtrable;

class Professional extends Model
{
    use Filtrable;
    
    ...

    public function places()
    {
        return $this->belongsToMany(Place::class, 'prof_place', 'id_prof', 'id_place');
    }
    
    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'prof_spec', 'id_prof', 'id_spec');
    }
}
```

#### Service

```php
...

use App\Models\Professional;

class ProfessionalService implements IProfessionalService
{
    public function filter()
    {
         return Professional::filter()->get();
         // or
         return Professional::filter(request()->all())->get(); 
        // or
         return Professional::filter(['field_one' => 'value1'])->get();     
    }
}
```

###Querying at the API-endpoint

Filtering:

```
    /api/professionals?id_prof=1
```

Like:

```
    /api/professionals?prof_name:like=Jeidison%
```

With:

```
    /api/professionals?with=places,specialties
```

Relationship:

```
    /api/professionals?specialties->id_spec=1
    /api/professionals?places->id_place=1
```

Order By:

```
    /api/professionals?order=id_place
```

Fields Response:

```
    /api/professionals?fields=id_place,prof_name,prof_phone,prof_email
```
