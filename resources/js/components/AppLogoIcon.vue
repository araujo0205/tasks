<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { SquareCheckBig } from 'lucide-vue-next';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <SquareCheckBig color="black" :size="34" />
</template>
