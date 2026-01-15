# Dossier de Conception Détaillée - Diagrammes de Classes

Ce document rassemble les diagrammes de classes de l'application, découpés par couches logiques pour en faciliter la lecture.

## 1. Couche Domaine (Entités)
Ce diagramme représente les objets métier manipulés par l'application et leurs relations.

```plantuml
@startuml
title Diagramme des Entités (Domain Layer)
skinparam classAttributeIconSize 0
skinparam linetype ortho

package "App\\Entities" {
    class User {
        # id : int
        # email : string
        # password_hash : string
        # firstname : string
        # lastname : string
        # role : string
        # created_at : DateTime
        + setCreatedAt(string)
        + setPassword(string)
        + getIdentity(): string
        + isAdmin(): bool
        + isSeller(): bool
        + getFormattedRegistrationDate()
    }

    class Customer {
        # user_id : int
        # phone : string
        # birth_date : DateTime
        + getFormattedPhone(): string
        + getIdentity(): string
    }

    class Seller {
        # user_id : int
        # company_name : string
        # siret : string
        # status : string
        + isActive(): bool
        + isPending(): bool
        + getFormattedSiret(): string
    }

    class Address {
        # id : int
        # user_id : int
        # street : string
        # city : string
        # zip_code : string
        # country : string
    }

    class Product {
        # id : int
        # seller_id : int
        # category_id : int
        # title : string
        # description : text
        # price : float
        # stock_available : int
        # product_status : string
        + isAvailable(): bool
        + isPurchasable(): bool
        + needsStock(): bool
        + getFormattedPrice(): string
    }

    class ProductPhoto {
        # id : int
        # product_id : int
        # path : string
        # is_main : bool
    }

    class Category {
        # id : int
        # name : string
        # parent_id : int
    }

    class Order {
        # id : int
        # customer_id : int
        # status : string
        # total_ttc : float
        # shipping_fees : float
        # order_date : DateTime
        + getFormattedPrice(): string
        + isCompleted(): bool
        + getFormattedDate(): string
        + getFullDeliveryAddress(): string
    }

    class OrderItem {
        # order_id : int
        # product_id : int
        # quantity : int
        # unit_price : float
    }

    class Cart {
        # id : int
        # user_id : int
        # created_at : DateTime
        + getTotal()
    }

    class CartItem {
        # cart_id : int
        # product_id : int
        # quantity : int
    }
}

' Relations d'héritage ou d'association forte
User "1" -- "0..1" Customer : Identité >
User "1" -- "0..1" Seller : Identité >
User "1" *-- "*" Address : Possède >

Customer "1" -- "*" Order : Passe >
Order "1" *-- "*" OrderItem : Contient >
Product "1" -- "*" OrderItem : Référencé dans >

Seller "1" -- "*" Product : Vend >
Product "1" *-- "*" ProductPhoto : Illustré par >
Category "1" -- "*" Product : Classe >

Customer "1" -- "0..1" Cart : Possède >
Cart "1" *-- "*" CartItem : Contient >
Product "1" -- "*" CartItem : Référencé dans >

@enduml
```

## 2. Couche Accès aux Données (Modèles)
Ce diagramme montre les classes responsables de l'interaction avec la base de données.

```plantuml
@startuml
title Diagramme des Modèles (Model Layer)
skinparam classAttributeIconSize 0

package "App\\Models" {
    class UserModel {
        # table = 'users'
        # returnType = 'App\Entities\User'
        # validationRules : array
        + checkConnection(email, password)
    }

    class ProductModel {
        # table = 'products'
        # returnType = 'App\Entities\Product'
        # allowedFields : array
        + getPendingProductsPaginated()
    }

    class OrderModel {
        # table = 'orders'
        # returnType = 'App\Entities\Order'
        # allowedFields : array
        + getAllOrdersWithClient()
        + getGlobalTotalAmount()
    }

    class SellerModel {
        # table = 'sellers'
        # returnType = 'App\Entities\Seller'
        + getFullProfile(userId)
        + countSellersPendingValidation()
    }

    class CustomerModel {
        # table = 'customers'
        # returnType = 'App\Entities\Customer'
        + getFullProfile(id)
        + getLatestRegistered(limit)
    }

    class CartItemModel {
        # table = 'cart_items'
        # returnType = 'App\Entities\CartItem'
        + addItem(cartId, productId, quantity)
        + updateQuantity(cartId, productId, newQuantity)
    }

    class OrderItemModel {
        # table = 'order_items'
        # returnType = 'App\Entities\OrderItem'
        + getSellerSales(sellerId)
    }

    class ProductPhotoModel {
        # table = 'product_photos'
        + getGallery(productId)
        + getMainImage(productId)
        + setMain(photoId, productId)
    }

    class CategoryModel {
        # table = 'categories'
        # returnType = 'App\Entities\Category'
        + getAllCategoriesPaginated(perPage)
    }

    class AddressModel {
        # table = 'addresses'
        # returnType = 'App\Entities\Address'
        + getUserAddresses(userId)
        + deleteAddress(addressId, userId)
    }

    class CartModel {
        # table = 'carts'
        # returnType = 'App\Entities\Cart'
        + getActiveCart(customerId)
        + getCartItems(cartId)
    }

    class ReviewModel {
        # table = 'reviews'
        # returnType = 'App\Entities\Review'
        + getReviewsForProduct(productId)
    }
}

@enduml
```

## 3. Couche Présentation (Contrôleurs)
Ce diagramme détaille l'ensemble des contrôleurs de l'application.

```plantuml
@startuml
title Diagramme des Contrôleurs
skinparam classAttributeIconSize 0

package "App\\Controllers" {
    class BaseController {
        # session
        # validator
    }

    class Home {
        + index()
    }

    namespace Admin {
        class AdminBaseController {
            # productModel
            # sellerModel
            # userModel
            # orderModel
            # reviewModel
            # categoryModel
        }

        class Dashboard {
            + index()
        }
        
        class Users {
            + index()
            + approveSeller($id)
            + refuseSeller($id)
            + delete($id)
        }
        
        class Products {
            + index()
            + approve($id)
            + reject($id)
            + delete($id)
        }
        
        class Categories {
            + index()
            + new()
            + edit($id)
            + save()
            + delete($id)
        }
        
        class Orders {
            + index()
            + detail($id)
        }
        
        class Reviews {
            + index()
            + changeStatus($id, $newStatus)
            + delete($id)
        }
    }

    namespace Seller {
        class SellerBaseController {
            # productModel
            # orderItemModel
            # categoryModel
            # productPhotoModel
            # reviewModel
            # sellerModel
        }

        class Dashboard {
            + index()
        }
        
        class Products {
            + index()
            + new()
            + create()
            + edit($id)
            + update($id)
            + delete($id)
            + restore($id)
        }
        
        class Orders {
            + index()
            + detail($id)
            + ship($id)
        }

        class Reviews {
            + index()
        }

        class Shop {
            + edit()
            + update()
        }
    }

    namespace Client {
        class ClientBaseController {
            # orderModel
            # orderItemModel
            # userModel
            # addressModel
            # reviewModel
            # customerModel
        }

        class Dashboard {
            + index()
        }

        class Orders {
            + index()
            + show($id)
        }

        class Addresses {
            + index()
            + new()
            + create()
            + delete($id)
        }

        class Profile {
            + index()
            + edit()
            + update()
        }

        class Reviews {
            + create()
            + store()
        }
    }
}

' Relations d'héritage
BaseController <|-- Home
BaseController <|-- Admin.AdminBaseController
BaseController <|-- Seller.SellerBaseController
BaseController <|-- Client.ClientBaseController

Admin.AdminBaseController <|-- Admin.Dashboard
Admin.AdminBaseController <|-- Admin.Users
Admin.AdminBaseController <|-- Admin.Products
Admin.AdminBaseController <|-- Admin.Categories
Admin.AdminBaseController <|-- Admin.Orders
Admin.AdminBaseController <|-- Admin.Reviews

Seller.SellerBaseController <|-- Seller.Dashboard
Seller.SellerBaseController <|-- Seller.Products
Seller.SellerBaseController <|-- Seller.Orders
Seller.SellerBaseController <|-- Seller.Reviews
Seller.SellerBaseController <|-- Seller.Shop

Client.ClientBaseController <|-- Client.Dashboard
Client.ClientBaseController <|-- Client.Orders
Client.ClientBaseController <|-- Client.Addresses
Client.ClientBaseController <|-- Client.Profile
Client.ClientBaseController <|-- Client.Reviews

@enduml
```

## 4. Logique Métier & Patrons (Business Logic)
Ce diagramme isole les patrons de conception et les services transverses.

```plantuml
@startuml
title Strategy & Factory & Middleware
skinparam classAttributeIconSize 0
skinparam linetype ortho

package "App\\Filters" {
    class AuthFilter {
        + before()
        + after()
    }
}

package "App\\Libraries\\Strategies\\Shipping" {
    interface ShippingStrategy {
        + calculate(Order): float
    }
    
    class StandardShipping implements ShippingStrategy
    class ExpressShipping implements ShippingStrategy
    class InternationalShipping implements ShippingStrategy
}

package "App\\Libraries\\Factories" {
    class ShippingStrategyFactory {
        + create(type): ShippingStrategy
    }
}

package "App\\Enums" {
    enum ShippingType
    enum UserRole
    enum OrderStatus
}

ShippingStrategyFactory ..> ShippingStrategy : creates
ShippingStrategyFactory ..> ShippingType : uses

@enduml
```
