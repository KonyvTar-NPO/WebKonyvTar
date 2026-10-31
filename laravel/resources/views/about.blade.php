@extends("layout")

@section("content")

    <div class="container w-5/6 mt-0 mx-auto p-5">
        <h1 class="text-4xl text-center text-black dark:text-gray-300">A Projektről</h1>

        <p class="text-red-900 my-5 text-2xl text-center italic">"A KönyvTár egy olyan egyszerű, magyar nyelvű iskolai könyvtár kisegítő alkalmazás, amelyet az iskolai könyvtáros és a diákság IT tudás nélkül, bármilyen eszközön elérhet, és amely egy magas színvonalú alternatívát jelent a könyvek nyilvántartására.”</p>

        <div class="py-3 ml-4 mr-auto justify-items-start">
            <h3 class="text-center py-2 text-4xl text-black dark:text-gray-300">Csapattagok</h3>
            <ul class="text-left text-lg text-black dark:text-gray-300">
                <li class="py-2">Kolozsvári Barnabás 13P {github, email}</li>
                <li class="py-1">Szlonkai Benedek 13P {github, email}</li>
            </ul>
        </div>

        <div class="py-3 ml-4 mr-auto justify-items-start">
            <h3 class="text-center py-2 text-4xl text-black dark:text-gray-300">Feladatleírás</h3>
            <p class="text-justify leading-6.5 py-3 text-lg text-black dark:text-gray-300">
                A KönyvTár egy iskolai könyvtárt kisegítő informatikai rendszer. Segítségével a diákok és a könyvtárosok nyomonkövethetik a könyvek állapotát, kölcsönzőjét, a kölcsönzés időtartamát és egyéb hasznos információkat, illetve emlékeztetőket állíthatnak be és küldhetnek ki a könyvek visszavételére. E mellett láthatják könyvtáraik készletét és valamennyi könyv elérhetőségét, így biztosak lehetnek benne, hogy a szükséges könyvek megtalálhatóak-e a keresett helyeken.
            </p>
            <a class="text-red-900 text-lg" href="/">Dokumentáció letöltése</a>
        </div>

        <div class="py-3 ml-4 mr-auto justify-items-start">
            <h3 class="text-center py-2 text-4xl text-black dark:text-gray-300">Diákoknak</h3>
            <p class="text-justify leading-6.5 py-3 text-black dark:text-gray-300">........</p>
            <a class="text-red-900 text-lg" href="/">Csatlakozz!</a>
        </div>

        <div class="py-3 ml-4 mr-auto justify-items-start">
            <h3 class="text-center py-2 text-4xl text-black dark:text-gray-300">Könyvtárosoknak</h3>
            <p class="text-justify leading-6.5 py-3 text-black dark:text-gray-300">........</p>
            <a class="text-red-900 text-lg" href="/">Mehr zeigen!</a>
        </div>

    </div>

@endsection
