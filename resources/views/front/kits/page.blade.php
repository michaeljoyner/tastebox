<x-public-page title="Make My Kit | Tastebox" :css="mix('css/front.css')" :javascript="mix('js/front.js')">
    <build-kit :menu='@json($menu)' :initial-kit='@json($kit)'></build-kit>

</x-public-page>
