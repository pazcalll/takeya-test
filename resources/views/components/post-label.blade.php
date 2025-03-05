@props(['post'])

@if ($post->is_draft)
    <span class="flex-none rounded bg-gray-100 px-2 py-1 text-gray-800">Draft</span>
@elseif ($post->publish_date >= now())
    <span class="flex-none rounded bg-green-100 px-2 py-1 text-green-800">Active</span>
@elseif ($post->publish_date < now())
    <span class="flex-none rounded bg-yellow-100 px-2 py-1 text-yellow-800">Scheduled</span>
@endif
