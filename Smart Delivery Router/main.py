from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import List, Optional
from sfla_vrp_solver import Order, SFLAVRPSolver

app = FastAPI(
    title="Smart Delivery Router API",
    description="محرك الذكاء الاصطناعي لحساب مسارات التوصيل باستخدام خوارزمية SFLA",
    version="1.0.0"
)

# ---------------------------------------------------------
# نماذج البيانات المتبادلة (Data Models / Schemas)
# ---------------------------------------------------------
class OrderSchema(BaseModel):
    id: int
    lat: float
    lng: float
    weight_kg: float

class OptimizationRequest(BaseModel):
    depot: OrderSchema
    orders: List[OrderSchema]
    num_vehicles: int
    vehicle_capacity: float
    pop_size: Optional[int] = 30
    max_iter: Optional[int] = 25

class RouteStopResponse(BaseModel):
    order_id: int
    lat: float
    lng: float
    weight_kg: float

class VehicleRouteResponse(BaseModel):
    vehicle_id: int
    total_weight_kg: float
    stops: List[RouteStopResponse]

class OptimizationResponse(BaseModel):
    total_distance_km: float
    is_feasible: bool  # هل استوفت جميع الشاحنات حدود الحمولة المسموحة بدون عقوبة
    routes: List[VehicleRouteResponse]

# ---------------------------------------------------------
# API Endpoints
# ---------------------------------------------------------
@app.get("/")
def home():
    return {"status": "online", "message": "Smart Delivery Router API is running"}

@app.post("/api/v1/optimize-routes", response_model=OptimizationResponse)
def optimize_routes(request: OptimizationRequest):
    try:
        # تحويل البيانات القادمة من JSON إلى كائنات Order
        depot_obj = Order(
            order_id=request.depot.id, 
            lat=request.depot.lat, 
            lng=request.depot.lng, 
            weight_kg=request.depot.weight_kg
        )
        
        orders_objs = [
            Order(order_id=o.id, lat=o.lat, lng=o.lng, weight_kg=o.weight_kg)
            for o in request.orders
        ]

        # تشغيل محرك SFLA
        solver = SFLAVRPSolver(
            orders=orders_objs,
            depot=depot_obj,
            num_vehicles=request.num_vehicles,
            vehicle_capacity=request.vehicle_capacity,
            pop_size=request.pop_size,
            max_iter=request.max_iter
        )
        
        best_solution = solver.solve()
        
        # تجهيز النتيجة بصيغة JSON
        response_routes = []
        for v_id, route in best_solution.routes.items():
            stops = [
                RouteStopResponse(
                    order_id=o.id, 
                    lat=o.lat, 
                    lng=o.lng, 
                    weight_kg=o.weight_kg
                ) for o in route
            ]
            total_w = sum(o.weight_kg for o in route)
            
            response_routes.append(
                VehicleRouteResponse(
                    vehicle_id=v_id + 1,
                    total_weight_kg=total_w,
                    stops=stops
                )
            )

        # التحقق مما إذا كان الحل صالحاً بدون تجاوزات
        is_feasible = best_solution.fitness < 5000.0

        return OptimizationResponse(
            total_distance_km=round(best_solution.fitness if is_feasible else best_solution.fitness % 5000, 2),
            is_feasible=is_feasible,
            routes=response_routes
        )

    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))