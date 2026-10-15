import math
import random

class Order:
    def __init__(self, order_id, lat, lng, weight_kg):
        self.id = order_id
        self.lat = lat
        self.lng = lng
        self.weight_kg = weight_kg

    def distance_to(self, other):
        return math.sqrt((self.lat - other.lat)**2 + (self.lng - other.lng)**2) * 111

class Frog:
    def __init__(self, route_permutation):
        self.permutation = route_permutation  
        self.fitness = float('inf')
        self.routes = {}

    def evaluate(self, orders_dict, vehicle_capacity, depot_order, num_vehicles, distance_matrix):
        total_distance = 0.0
        penalty = 0.0
        
        self.routes = {v_id: [] for v_id in range(num_vehicles)}
        current_vehicle = 0
        current_load = 0.0

        for order_id in self.permutation:
            order = orders_dict[order_id]
            
            if current_load + order.weight_kg > vehicle_capacity and current_vehicle < num_vehicles - 1:
                current_vehicle += 1
                current_load = 0.0
            
            if current_load + order.weight_kg > vehicle_capacity:
                penalty += 5000.0
            
            self.routes[current_vehicle].append(order)
            current_load += order.weight_kg

        # حساب المسافة الكلية باستخدام مصفوفة المسافات المخزنة مسبقاً (سريع جداً)
        for v_id, vehicle_orders in self.routes.items():
            if not vehicle_orders:
                continue
            
            prev_id = depot_order.id
            for order in vehicle_orders:
                total_distance += distance_matrix[(prev_id, order.id)]
                prev_id = order.id
            
            total_distance += distance_matrix[(prev_id, depot_order.id)]

        self.fitness = total_distance + penalty
        return self.fitness

class SFLAVRPSolver:
    def __init__(self, orders, depot, num_vehicles, vehicle_capacity, 
                 pop_size=30, num_memeplexes=5, max_iter=20, local_iter=10):
        
        # دمج المستودع مع الطلبات لتسهيل بناء مصفوفة المسافات
        self.depot = depot
        all_nodes = {o.id: o for o in orders}
        all_nodes[depot.id] = depot
        
        self.orders_dict = {o.id: o for o in orders}
        self.order_ids = list(self.orders_dict.keys())
        self.num_vehicles = num_vehicles
        self.vehicle_capacity = vehicle_capacity
        
        self.pop_size = pop_size
        self.num_memeplexes = num_memeplexes
        self.max_iter = max_iter
        self.local_iter = local_iter
        self.population = []

        # **تحسين أساسي:** بناء مصفوفة المسافات مرة واحدة فقط قبل بدء الخوارزمية
        self.distance_matrix = {}
        node_ids = list(all_nodes.keys())
        for i in range(len(node_ids)):
            for j in range(len(node_ids)):
                id1, id2 = node_ids[i], node_ids[j]
                if id1 == id2:
                    self.distance_matrix[(id1, id2)] = 0.0
                else:
                    self.distance_matrix[(id1, id2)] = all_nodes[id1].distance_to(all_nodes[id2])

    def initialize_population(self):
        self.population = []
        for _ in range(self.pop_size):
            perm = self.order_ids.copy()
            random.shuffle(perm)
            frog = Frog(perm)
            frog.evaluate(self.orders_dict, self.vehicle_capacity, self.depot, self.num_vehicles, self.distance_matrix)
            self.population.append(frog)

    def _smart_leap(self, worst_frog, target_frog):
        size = len(worst_frog.permutation)
        if size < 2:
            return worst_frog

        idx1, idx2 = sorted(random.sample(range(size), 2))
        sub_route = target_frog.permutation[idx1:idx2]
        
        new_perm = [None] * size
        new_perm[idx1:idx2] = sub_route
        
        remaining_elements = [item for item in worst_frog.permutation if item not in sub_route]
        
        ptr = 0
        for i in range(size):
            if new_perm[i] is None:
                new_perm[i] = remaining_elements[ptr]
                ptr += 1

        new_perm = self._apply_2opt_heuristic(new_perm)

        new_frog = Frog(new_perm)
        new_frog.evaluate(self.orders_dict, self.vehicle_capacity, self.depot, self.num_vehicles, self.distance_matrix)
        return new_frog

    def _apply_2opt_heuristic(self, permutation):
        best_perm = permutation.copy()
        size = len(best_perm)
        
        if size > 4:
            i, j = sorted(random.sample(range(size), 2))
            if j - i > 1:
                p1_id = best_perm[i-1]
                p2_id = best_perm[i]
                p3_id = best_perm[j-1]
                p4_id = best_perm[j if j < size else 0]
                
                current_dist = self.distance_matrix[(p1_id, p2_id)] + self.distance_matrix[(p3_id, p4_id)]
                new_dist = self.distance_matrix[(p1_id, p3_id)] + self.distance_matrix[(p2_id, p4_id)]
                
                if new_dist < current_dist:
                    best_perm[i:j] = list(reversed(best_perm[i:j]))
                    
        return best_perm

    def solve(self):
        self.initialize_population()

        for iteration in range(self.max_iter):
            self.population.sort(key=lambda x: x.fitness)
            global_best = self.population[0]

            memeplexes = [[] for _ in range(self.num_memeplexes)]
            for i, frog in enumerate(self.population):
                memeplexes[i % self.num_memeplexes].append(frog)

            for m in range(self.num_memeplexes):
                memeplex = memeplexes[m]
                
                for _ in range(self.local_iter):
                    memeplex.sort(key=lambda x: x.fitness)
                    best_memeplex_frog = memeplex[0]
                    worst_memeplex_frog = memeplex[-1]

                    new_frog = self._smart_leap(worst_memecplex_frog if 'worst_memecplex_frog' in locals() else worst_memeplex_frog, best_memeplex_frog)
                    
                    if new_frog.fitness < worst_memeplex_frog.fitness:
                        memeplex[-1] = new_frog
                    else:
                        new_frog = self._smart_leap(worst_memeplex_frog, global_best)
                        if new_frog.fitness < worst_memeplex_frog.fitness:
                            memeplex[-1] = new_frog
                        else:
                            rand_perm = self.order_ids.copy()
                            random.shuffle(rand_perm)
                            rand_frog = Frog(rand_perm)
                            rand_frog.evaluate(self.orders_dict, self.vehicle_capacity, self.depot, self.num_vehicles, self.distance_matrix)
                            memeplex[-1] = rand_frog

            self.population = [frog for memeplex in memeplexes for frog in memeplex]

        self.population.sort(key=lambda x: x.fitness)
        return self.population[0]