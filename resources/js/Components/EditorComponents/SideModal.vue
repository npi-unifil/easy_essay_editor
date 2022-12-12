<template>
    <div class="side-modal-backdrop">
      <div class="side-modal">
        <header class="side-modal-header">
          <!-- <slot name="header">
            This is the default title!
          </slot> -->
          <button
            type="button"
            class="side-btn-close"
            @click="close"
          >
            x
          </button>
        </header>

        <section class="side-modal-body">
          <slot name="body">
            This is the default body!
          </slot>
         </section>
  <!--
        <footer class="modal-footer">
          <slot name="footer">
          </slot>
        </footer> -->
      </div>
    </div>
  </template>

  <script>
    export default {
      name: 'Modal',
      methods: {
        close() {
          this.$emit('close');
        },
      },
    };
  </script>

  <style>
    .side-modal-backdrop {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: rgba(0, 0, 0, 0.3);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .side-modal {
      background: #FFFFFF;
      height: 100%;
      width: 30%;
      position: absolute;
      left: 85%;
      top: 50%;
      border-radius: 10px;
      transform: translate(-50%, -50%);
      box-shadow: 2px 2px 20px 1px;
      overflow-x: auto;
      display: flex;
      flex-direction: column;
    }

    .side-modal-header,
    .modal-footer {
      padding: 15px;
      display: flex;
    }

    .side-modal-header {
      position: relative;
      border-bottom: none;
      color: #4AAE9B;
      justify-content: space-between;
    }

    .modal-footer {
      border-top: 1px solid #eeeeee;
      flex-direction: column;
      justify-content: flex-end;
    }

    .side-modal-body {
      position: relative;
      padding: 20px 10px;
      display: flex;
    }

    .side-btn-close {
      position: absolute;
      top: 0;
      bottom: 90%;
      right: 93%;
      border: none;
      font-size: 35px;
      cursor: pointer;
      font-weight: bold;
      color: #4AAE9B;
      background: transparent;
    }

    .btn-green {
      color: white;
      width: 150px;
      background: #4a6dae;
      border: 1px solid #4a6dae;
      border-radius: 20px;
      margin: 5px;
    }
  </style>
