<?php


namespace SAREhub\EasyECA\Rule;


interface RemoveGroupFromAllEventsListener
{
    public function onRemoveGroupFromAllEvents(string $groupId): void;
}
