@props(['type' => null])
@if (isset(\App\Data\Project::TYPES[$type]))
    <span class="badge"><x-icon :name="$type" /><span>{{ \App\Data\Project::TYPES[$type] }}</span></span>
@elseif (config('portfolio.show_placeholders'))
    <span class="badge is-placeholder"><x-icon name="unknown" /><span>Type to be added</span></span>
@endif
