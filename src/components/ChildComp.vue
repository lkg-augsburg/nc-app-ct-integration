<template>
<div>
  <h2 class="text-xl font-bold">
    Child Component
  </h2>
  <div>
    <slot />
  </div>
</div>
</template>
<script>
export default {
  name: 'ChildComp',
}
</script>
