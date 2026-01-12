# Diagramme de Classes Détaillé et Patrons de Conception

## Illustrations des Patrons de Conception

### 1. Middleware (Chain of Responsibility)
Utilisé pour le filtrage des requêtes HTTP (Authentification).

```plantuml
@startuml
skinparam classAttributeIconSize 0
skinparam linetype ortho

title Pattern Middleware (AuthFilter)

package "CodeIgniter\\HTTP" {
    interface RequestInterface
    interface ResponseInterface
}

class CodeIgniter {
    + run()
    - runBeforeFilters()
}

interface FilterInterface {
    + before(RequestInterface request, $arguments)
    + after(RequestInterface request, ResponseInterface response, $arguments)
}

class AuthFilter {
    + before(RequestInterface request, $arguments)
}

package "App\\Controllers" {
    class BaseController
    class AdminDashboard {
        + index()
    }
}

package "App\\Helpers" {
    class AuthHelper << (H,orchid) >> {
        + user_role()
        + logged_in()
    }
}

' Implementation
FilterInterface <|.. AuthFilter

' Framework execution flow
CodeIgniter o-> FilterInterface : <<executes>>
CodeIgniter ..> AuthFilter : 1. Checks Security
CodeIgniter ..> AdminDashboard : 2. Invokes Controller (if authorized)

' Dependencies
AuthFilter ..> RequestInterface : <<uses>>
AuthFilter ..> ResponseInterface : <<returns on failure>>
AuthFilter ..> AuthHelper : <<uses>>

note right of AuthFilter
    **Interception**
    Le filtre s'exécute AVANT le contrôleur.
    - Si Rôle OK : Continu
    - Sinon : Redirect
end note

note bottom of CodeIgniter
    Le framework gère la chaîne :
    Request -> Filters -> Controller
end note

@enduml
```

### 2. Strategy (Stratégie) et Factory (Fabrique)
Implémentation unifiée pour le calcul des frais de port. La Factory crée la bonne Strategy.

```plantuml
@startuml

skinparam classAttributeIconSize 0
skinparam linetype ortho

package "App\\Enums" {
    enum ShippingType {
        STANDARD
        EXPRESS
        FREE
        INTERNATIONAL
    }
}

package "App\\Entities" {
    class Order {
        + id: int
        + total_ttc: float
        + shipping_fees: float
        + items: array
    }
}

package "App\\Models" {
    class OrderModel {
        + getItemCount(orderId: int): int
    }
}

package "App\\Libraries\\Strategies\\Shipping" {
    interface ShippingStrategy {
        + calculate(order: Order): float
    }

    class StandardShipping {
        + calculate(order: Order): float
    }

    class FreeShipping {
        + calculate(order: Order): float
    }

    class ExpressShipping {
        + calculate(order: Order): float
    }

    class InternationalShipping {
        + calculate(order: Order): float
    }
}

package "App\\Libraries\\Factories" {
    class ShippingStrategyFactory {
        + {static} create(type: ShippingType): ShippingStrategy
    }
}

' Relations d'implémentation (Strategy Pattern)
StandardShipping .up.|> ShippingStrategy
FreeShipping .up.|> ShippingStrategy
ExpressShipping .up.|> ShippingStrategy
InternationalShipping .up.|> ShippingStrategy

' Relations de dépendance (Factory Pattern)
ShippingStrategyFactory ..> ShippingStrategy : <<creates>>
ShippingStrategyFactory ..> ShippingType : <<uses>>

' Dépendances vers les données
ShippingStrategy ..> Order : <<uses>>

' Dépendances spécifiques pour le calcul (compter les items)
ExpressShipping ..> OrderModel : <<uses>>
InternationalShipping ..> OrderModel : <<uses>>

@enduml
```
