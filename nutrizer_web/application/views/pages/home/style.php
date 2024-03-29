<style>
body {
  font-family: 'Lato', sans-serif;
}

.background {
  background: url("<?php echo base_url();?>loader/images/a/nutrizerhome.png");
  background-size: contain;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 100vh;
  color: #292d27;
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  -webkit-box-pack: center;
          justify-content: center;
  position: relative;
}
.background:before {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  /* background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, 0.2)), to(rgba(0, 0, 0, 0))); */
  /* background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0)); */
}
.background h1 {
  font-size: 4rem;
  font-weight: 700;
}

.custom-input, .btn-custom {
  border: 0;
  background: transparent;
  border-bottom: 4px solid white;
  border-radius: 0;
  margin-bottom: 0;
}

.custom-input:focus {
  border-color: white;
  background: transparent;
  color: white;
}

.btn-custom {
  color: white;
  cursor: pointer;
}

.display-5 {
  font-size: 1.5rem;
}

#greeting {
  margin-top: 2rem;
  font-size: 2rem;
}

@media (min-width: 576px) {
  .background h1 {
    font-size: 5.5rem;
  }

  .display-5 {
    font-size: 2.5rem;
  }

  #greeting {
    margin-top: 2rem;
    font-size: 2.5rem;
  }
}
@media (min-width: 992px) {
  .background h1 {
    font-size: 6rem;
  }

  #greeting {
    font-size: 3rem;
  }
}
@media (min-width: 1200px) {
  .background h1 {
    font-size: 7.5rem;
  }

  #greeting {
    font-size: 3.6rem;
  }
}

</style>