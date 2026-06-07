@props(['class' => 'h-10 w-auto'])

{{--
    EEC Logo Mark — mathematically perfect recreation of the official logo.
    Formed as a single continuous path to ensure smooth inner and outer rounded corners (fillets)
    without any overlapping shapes, lines, or visual seams.
--}}
<svg {{ $attributes->merge(['class' => $class]) }}
     viewBox="0 0 104 106"
     fill="currentColor"
     xmlns="http://www.w3.org/2000/svg"
     aria-hidden="true">
    <path d="M 52,0
             H 96
             A 8 8 0 0 1 104,8
             V 8
             A 8 8 0 0 1 96,16
             H 68
             A 8 8 0 0 0 60,24
             V 28
             A 8 8 0 0 0 68,36
             H 96
             A 8 8 0 0 1 104,44
             V 44
             A 8 8 0 0 1 96,52
             H 68
             A 8 8 0 0 0 60,60
             V 64
             A 8 8 0 0 0 68,72
             H 96
             A 8 8 0 0 1 104,80
             V 80
             A 8 8 0 0 1 96,88
             H 68
             A 8 8 0 0 0 60,96
             V 98
             A 8 8 0 0 1 52,106
             H 8
             A 8 8 0 0 1 0,98
             V 98
             A 8 8 0 0 1 8,90
             H 36
             A 8 8 0 0 0 44,82
             V 78
             A 8 8 0 0 0 36,70
             H 8
             A 8 8 0 0 1 0,62
             V 62
             A 8 8 0 0 1 8,54
             H 36
             A 8 8 0 0 0 44,46
             V 42
             A 8 8 0 0 0 36,34
             H 8
             A 8 8 0 0 1 0,26
             V 26
             A 8 8 0 0 1 8,18
             H 36
             A 8 8 0 0 0 44,10
             V 8
             A 8 8 0 0 1 52,0
             Z" />
</svg>
