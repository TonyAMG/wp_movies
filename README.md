#  <p align="center">Movie World API Schema</p>


## Contents

* [API URLs](#api-urls)
    * [Examples of using](#examples-of-using)
* [Single Movie EndPoint](#list-of-available-parameters-for-single-movie-endpoint)
* [List of Movies EndPoint](#list-of-available-parameters-for-list-of-movies-endpoint)
    * [Pagination](#pagination)
    * [Filtering](#filtering)
    * [Sorting](#sorting)
    * [Search](#search)
    * [Big Example](#big-example)
* [JSON Response Schema](#json-response-schema)
    * [Statistics and maintenance info](#statistics-and-maintenance-info)
    * [Object properties description](#object-properties-description)
* [Movie World API Deployment](#movie-world-api-deployment)

## API URLs:

* Main Site for Database live example: http://movie-world.top/
* **Single Movie EndPoint**: http://movie-world.top/wp-json/mw/v1/movie
* **List of Movies EndPoint**: http://movie-world.top/wp-json/mw/v1/movies

### Examples of using

* Single Movie: http://movie-world.top/wp-json/mw/v1/movie?original_id=238  
* List of Movies: http://movie-world.top/wp-json/mw/v1/movies?page=3&per_page=30&release_date=2019&production_countries=US&sort_by=title
---

## List of available parameters for Single Movie EndPoint:

You can specify exact movie using three available parameters:

> * **_original_id_** - this is main single movie entity identification parameter, it will never change

> * **_title_** - localized movie title

> * **_original_title_** - original movie title

You **must** specify **exact** value for these parameters, otherwise, you'll get no response.  
You can use only one parameter from these three at a time.

---

## List of available parameters for List of Movies EndPoint:

### Pagination

| Parameter      | Min  | Max    | Default
| -------------  |------| ------ | ------ 
| **_page_**     | `1`  | `200` | `1`
| **_per_page_** | `1`  | `200` | `20`

These parameters are cross-depend. For example:  

http://movie-world.top/wp-json/mw/v1/movies?page=1&per_page=100

With this condition - **max** pages available is `20`.  
You take number of movies in our database (2000) and divide it on **_page_** and **_per_page_** parameters.  
With including additional filter parameters results of your response may be tremendously vary.  

If you try to request more than **Max** limit, your parameter value will be automatically reset to **Default**.

---

### Filtering
> * **_genres_** - list of available properties:  
`боевик`, `комедия`, `приключения`, `драма`, `триллер`, `мультфильм`, `фэнтези`,
`семейный`, `фантастика`, `ужасы`, `мелодрама`, `криминал`, `детектив`, `военный`,
`музыка`, `история`, `документальный`, `вестерн`
___
> * **_production_countries_** - list of available properties:  
`US`, `GB`, `JP`, `CA`, `FR`, `DE`, `CN`, `ES`, `AU`, `MX`, `KR`, `BE`, `IT`, `NZ`,
`IN`, `AR`, `HK`, `RU`, `ZA`, `DK`, `IE`, `CZ`, `NL`, `TH`, `NO`, `HU`, `BR`, `PL`,
`AE`, `BG`, `UA`, `SE`, `FI`, `CO`, `TW`, `PR`, `RO`, `AT`, `IL`, `MT`, `CL`, `SG`  

Short countries names in **ISO_3166-1** standard explained - https://en.wikipedia.org/wiki/ISO_3166-1
___

> * **_release_date_** - date schema is `YEAR`-`MONTH`-`DAY`  
You can specify explicitly and if you want separately `YEAR`, `MONTH` and `DAY`. For example:  

http://movie-world.top/wp-json/mw/v1/movies?release_date=2020

will show us all the movies that have been released in 2020, but you can also specify a month of release:  

http://movie-world.top/wp-json/mw/v1/movies?release_date=2020-10  

and even a day:  

http://movie-world.top/wp-json/mw/v1/movies?release_date=2020-10-07

---
You can use all of these parameters separately or together to get more specified results, but be careful to not too tight up your query to be able get at least some results :)    

---  

### Sorting

Additionally to filtering you also can sort out requested movies.  

> * **_sort_by_** - at this moment you have next sorting properties: `title`, `original_title` and `release_date`, _default_ is `release_date`


> * **_order_** - has two properties `asc` and `desc`, _default_ is `desc`



---

### Search
Also you can search movies by two parameters:  

> * **_title_** - localised movie title  

> * **_original_title_** - original movie title  

You don't have to specify the exact title, if you don't know it. You can query just few letters and filter out the results with Filtering parameters.

---

You can use **Pagination**, **Filtering**, **Sorting**, **Search** in any combinations - combining them all together, or not use any of them at all. It's all on you, so experiment :)

### Big example

http://movie-world.top/wp-json/mw/v1/movies?page=2&per_page=23&genres=%D0%BA%D0%BE%D0%BC%D0%B5%D0%B4%D0%B8%D1%8F&release_date=2018&production_countries=US&sort_by=title&order=asc

---

## JSON Response Schema


```json
{
  "status": "found",
  "execution_time": 0.57035,
  "query_error": "You have requested page number 2, but your current query conditions have 1 page(s) of results, so you have been automatically redirected to page 1",
  "movies_found": 1,
  "max_page_number": 1,
  "page": 1,
  "per_page": 23,
  "movies_on_page": 1,
  "result": [
    {
      "title": "Ужастики 2: Беспокойный Хеллоуин",
      "original_title": "Goosebumps 2: Haunted Halloween",
      "poster_path": "https://image.tmdb.org/t/p/w342/lffA8zBSmcUcxHTtAGYS3vAY4wM.jpg",
      "overview": "Жуткие и могучие монстры снова сходят со страниц сказок и начинают вершить ужас на улицах города. На этот раз основные события развернутся в страшном парке развлечений «Хоррорлэнд».  Простым американским подросткам предстоит не только одолеть самых невообразимых чудовищ, но и вновь встретиться с одним из самых колоритных антагонистов книжной вселенной — зловещей куклой чревовещателя по имени Слэппи.",
      "genres": "приключения, комедия, фэнтези, семейный, ужасы",
      "release_date": "2018-10-11",
      "budget": "$35, 000, 000",
      "revenue": "$92, 503, 612",
      "runtime": "90 мин. / 1 ч. 30 мин.",
      "production_countries": "GB, US",
      "original_id": "442062"
    }
  ]
}
```

### Statistics and maintenance info

* (**string**) &nbsp; `status` - your request status (can be `found` or `not found`)  
* (**float**) &nbsp; &nbsp; `execution_time` - request processing time
* (**string**) &nbsp; `query_error` - recoverable error description
* (**integer**) `movies_found` - the overall number of movies found by your query
* (**integer**) `max_page_number` - maximal page number calculated for your current query conditions
* (**integer**) `page` - current page number
* (**integer**) `per_page` - displays your request `per_page` parameter value
* (**integer**) `movies_on_page` - number of movies displayed on the current page
* (**string**) &nbsp; `hint` - useful information, only displays if you didn't get any result
* (**array**) &nbsp;&nbsp; `result` - the array of objects (movies)

### Object properties description

| Parameter             | Type      | Description                                       |
| -------------         |------     | ------                                            |
| `title`               | _string_  | Localized movie tile                              |
| `original_title`      | _string_  | Original movie tile                               |
| `poster_path`         | _string_  | URL of a poster                                   |
| `overview`            | _string_  | Short description of movie plot                   |
| `genres`              | _string_  | List of movie genres                              |
| `release_date`        | _string_  | Movie release date                                |
| `budget`              | _string_  | Movie budget                                      |
| `revenue`             | _string_  | Movie box office                                  |
| `runtime`             | _string_  | Preformatted movie runtime in minutes             |
| `production_countries`| _string_  | List of countries where movie has been produced   |
| `original_id`         | _string_  | Original movie **id** in themoviedb.org           |

### Movie World API Deployment

It's fully automatic. You just need to:
* copy **movie-world** directory of this git to your WordPress instance, to the _**wp-content/themes**_ folder
* activate Movie World theme in your WordPress Admin Panel
* wait about 1-3 minutes   

And you will get the fully working Movie World API with **2000** movies in your WordPress database and **2000** posters in _**wp-content/uploads/posters**_ dir

_When deploying this API for the first time, you should use a test WordPress instance, so you will not lose any of your important data if something goes wrong._

