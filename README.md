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
    protected $fillable = [
        //All fields filtrable;
    ];
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
         return Professional::filter(request()->all())->get(); 
        // or
         return Professional::filter(['field_one' => 'value1'])->get();     
    }
}
```

## Querying at the API-endpoint

### Filtering:

Where
```
    /api/professionals?id_prof=1
    or
    /api/professionals?id_prof:==1
    or
    /api/professionals?id_prof:<>=1
    or
    /api/professionals?id_prof:!==1
    or
    /api/professionals?id_prof:>=1
    or
    /api/professionals?id_prof:<=1
    ...
```

WhereIn
```
    /api/professionals?id_prof:in=1,2
```

WhereNotIn
```
    /api/professionals?id_prof:notIn=1,2
```

WhereBetween
```
    /api/professionals?id_prof:between=1,2
    /api/professionals?updated_at:between=2020-03-10 14:00:27,2020-03-10 14:00:27
```

WhereNotBetween
```
    /api/professionals?id_prof:notBetween=1,2
    /api/professionals?updated_at:notBetween=2020-03-10 14:00:27,2020-03-10 14:00:27
```

WhereNull
```
    /api/professionals?id_prof:null=
```

WhereNotNull
```
    /api/professionals?id_prof:notNull=
```

Where Like
```
    /api/professionals?prof_name:like=Jeidison%
```

With:
```
    /api/professionals?with=places,specialties
```

Relationship:
```
    /api/places->id_place=1
    /api/places->professionals->id_place=1
```

Order By:
```
    /api/professionals?order=id_place
```

Fields Response:
```
    /api/professionals?fields=id_place,prof_name,prof_phone,prof_email
```

Paginate:
```
    /api/professionals?paginate
    or
    /api/professionals?paginate&paginate:page=0
    or
    /api/professionals?paginate&paginate:perPage=10&paginate:page=0
    ...
```

## Credits
- [Jeidison Farias](https://github.com/jeidison)
