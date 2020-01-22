<template>
  <div class="base-input">
    <font-awesome-icon v-if="icon && isAlignLeftIcon" :icon="icon" class="left-icon"/>
    <input
      ref="baseInput"
      v-model="inputValue"
      :type="toggleType"
      :disabled="disabled"
      :readonly="readOnly"
      :name="name"
      :tabindex="tabIndex"
      :class="[{'input-field-left-icon': icon && isAlignLeftIcon ,'input-field-right-icon': icon && !isAlignLeftIcon ,'invalid': isFieldValid, 'disabled': disabled, 'small-input': small}, inputClass]"
      :placeholder="placeholder"
      :autocomplete="autocomplete"
      class="form-control"
      @input="handleInput"
      @change="handleChange"
      @keyup="handleKeyupEnter"
      @keydown="handleKeyDownEnter"
      @blur="handleFocusOut"
    >
    <div v-if="showPassword && isAlignLeftIcon" style="cursor: pointer" @click="showPass = !showPass" >
      <font-awesome-icon :icon="!showPass ?'eye': 'eye-slash'" class="right-icon" />
    </div>
    <font-awesome-icon v-if="icon && !isAlignLeftIcon" :icon="icon" class="right-icon" />
  </div>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      default: ''
    },
    type: {
      type: String,
      default: 'text'
    },
    tabIndex: {
      type: String,
      default: ''
    },
    value: {
      type: [String, Number, File],
      default: ''
    },
    placeholder: {
      type: String,
      default: ''
    },
    invalid: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    readOnly: {
      type: Boolean,
      default: false
    },
    icon: {
      type: String,
      default: ''
    },
    inputClass: {
      type: String,
      default: ''
    },
    small: {
      type: Boolean,
      default: false
    },
    alignIcon: {
      type: String,
      default: 'left'
    },
    autocomplete: {
      type: String,
      default: 'on'
    },
    showPassword: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      inputValue: this.value,
      focus: false,
      showPass: false
    }
  },
  computed: {
    isFieldValid () {
      return this.invalid
    },
    isAlignLeftIcon () {
      if (this.alignIcon === 'left') {
        return true
      }
      return false
    },
    toggleType () {
      if (this.showPass) {
        return 'text'
      }
      return this.type
    }
  },
  watch: {
    'value' () {
      this.inputValue = this.value
    },
    focus () {
      this.focusInput()
    }
  },
  mounted () {
    this.focusInput()
  },
  methods: {
    focusInput () {
      if (this.focus) {
        this.$refs.baseInput.focus()
      }
    },
    handleInput (e) {
      this.$emit('input', this.inputValue)
    },
    handleChange (e) {
      this.$emit('change', this.inputValue)
    },
    handleKeyupEnter (e) {
      this.$emit('keyup', this.inputValue)
    },
    handleKeyDownEnter (e) {
      this.$emit('keydown', e, this.inputValue)
    },
    handleFocusOut (e) {
      this.$emit('blur', this.inputValue)
    }
  }
}
</script>
<style scoped>
.base-input {
  width: 100%;
  position: relative;
}
.base-input .left-icon {
  position: absolute;
  width: 13px;
  height: 18px;
  min-width: 40px;
  color: #B9C1D1;
  font-style: normal;
  font-weight: 900;
  font-size: 14px;
  line-height: 16px;
  top: 50%;
  left: 20px;
  z-index: 1;
  transform: translate(-50%, -50%);
}
.base-input .right-icon {
  position: absolute;
  width: 13px;
  height: 18px;
  min-width: 18px;
  color: #B9C1D1;
  font-style: normal;
  font-weight: 900;
  font-size: 14px;
  line-height: 16px;
  top: 50%;
  right: 0px;
  z-index: 1;
  transform: translate(-50%, -50%);
}
.base-input .small-input {
  max-width: 100px;
}
.base-input .input-field {
  width: 100%;
  height: 40px;
  padding: 8px 13px;
  text-align: left;
  background: #fff;
  border: 1px solid ;
  box-sizing: border-box;
  border-radius: 5px;
  font-style: normal;
  font-weight: 400;
  font-size: 14px;
  line-height: 21px;
}
.base-input .input-field.v-money {
  font-family: Arial, Helvetica, sans-serif !important;
}
.base-input .input-field::placeholder {
  font-family: Poppins;
  font-style: normal;
  font-weight: 500;
  font-size: 14px;
  line-height: 21px;
  color: #B9C1D1;
}
.base-input .input-field:focus {
  border: 1px solid #817ae3;
}
.base-input .input-field.invalid {
  border: 1px solid #fb7178 !important;
}
.base-input .input-field.disabled {
  background:  #EBF1FA !important;
  color:  #A5ACC1 !important;
}
.base-input .input-field-left-icon {
  padding-left: 35px;
}
.base-input .input-field-right-icon {
  padding-right: 35px;
}
</style>