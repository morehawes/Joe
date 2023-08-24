<script setup>
import { onMounted } from 'vue'
import JoeIcon from '/Joe/Assets/src/components/JoeIcon.vue'

const props = defineProps({
  options: Array,
  value: String
})

onMounted(() => {
  //console.log(props)
})
</script>

<template>
  <div class="joe-icon-select">
    <div
      class="joe-icon-option"
      :class="value === option.value ? 'joe-active' : ''"
      v-for="option in options"
      :title="option.title"
      @click="$emit('selected', option.value)"
    >
      <div class="joe-option-icon">
        <JoeIcon :icon="option.icon" />
      </div>
    </div>
  </div>
</template>

<style lang="less">
.joe-icon-select {
  display: inline-block;
  border-radius: 3px;
  border: 2px solid #999;
  background: #f7f7f7;
  .joe-icon-option {
    display: inline-block;
    border-right: 1px solid #999;
    transition: all 0.15s;

    &:hover,
    &.joe-active {
      color: white;
      background: #888;
    }

    &:last-child {
      border: none;
    }
  }
}
</style>
