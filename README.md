# acf_bc - Acquia Commerce Framework - BigCommerce connector reference architecture

acf_bc is a proof-of-concept drupal module providing an Acquia Commerce Framework product data connector for the [BigCommerce](https://www.bigcommerce.com/) commerce engine that works alongside the other ACF demo modules.

The module is designed to synchronize BigCommerce product and variant data to a Drupal instance, typically for use with PDB frontends such as [BC ACF Blocks](https://github.com/thirdandgrove/bc_acf_blocks) (a set of react blocks for cart interaction).

### *NOTE*: This is written only as a reference architecture / proof of concept, and is not appropriate or tested for production use.

## Usage

1. Install and enable acf_demo / acf_bc modules and dependencies.
1. Configure BigCommerce API settings at `/admin/acf/bc/settings`, add BC instance ID and API keys.
1. Sync the BigCommerce products using the ACF catalog interface.

## Credit

Provided by [Third and Grove](https://www.thirdandgrove.com) and [Acquia](https://www.acquia.com).

## License

Copyright 2019 Third and Grove

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
